<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 28-4-2017
 * Time: 02:49
 */

namespace Stormyy\B3\Http;


use App\Http\Controllers\Controller;
use App\Server;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Stormyy\B3\Events\ScreenshotTaken;
use Stormyy\B3\Helper\B3Database;
use Stormyy\B3\Helper\Cod4Server;
use Stormyy\B3\Helper\PermissionHelper;
use Stormyy\B3\Models\B3Server;
use Stormyy\B3\Models\ChatLog;
use Stormyy\B3\Models\Group;
use Stormyy\B3\Models\Penalty;
use Stormyy\B3\Models\Player;
use Stormyy\B3\Models\Screenshot;
use q3tool;
use Storage;
use Illuminate\Support\Facades\Input;

class B3ServerController extends Controller
{
    public function getList()
    {
        return view('b3::server.list')->with(['servers' => B3Server::get()]);
    }

    public function get(B3Server $server)
    {
        /** @var Server $server */
        $b3database = (new B3Database($server));
        $admins = Player::where('group_bits', '>=', '8')->orderBy('group_bits', 'desc')->orderBy('id', 'asc')->get();
        $activebans = Penalty::whereIn('type', ['Ban', 'TempBan'])->where('inactive', 0)->where(function($query) {
            $query->where('time_expire', '>', Carbon::now()->getTimestamp());
            $query->orWhere('time_expire', '-1');
        })->orderBy('time_add', 'desc')->paginate(30);


        \View::share('myplayer', $b3database->getMyPlayer());
        return view('b3::server.item')->with(['server' => $server, 'admins' => $admins, 'activebans' => $activebans]);
    }

    public function getPlayers(B3Server $server)
    {
        return \Cache::remember('server-'.$server->id.'-players', Carbon::now()->addSeconds(5), function() use($server) {
            /** @var Server $server */
            $b3database = new B3Database($server);
            $b3users = ($b3database)->getCurrentClients();
            $b3CurrentUserList = [];
            foreach ($b3users as $b3user) {
                $b3user->screenshots = $server->screenshots()->where('guid', (int)$b3user->GUID)->orderBy('created_at', 'desc')->get();;
                $b3user->IP = PermissionHelper::ipToFlag($b3user->IP) . " " . PermissionHelper::ip($b3user->IP);
                if (\Auth::check()) {
                    $b3user->AllowScreenshot = \Auth::user()->can('screenshot', [$server]);
                }
                $b3user->bannedOnOtherServers = $b3database->isPlayerBanned($b3user->GUID);
                $b3CurrentUserList[] = $b3user;
            }

            return $b3CurrentUserList;
        });
    }

    public function postScreenshot(Request $request)
    {

        $identkey = Input::get('identkey');
        $server = B3Server::where('identifier', $identkey)->first();
        $serverport = Input::get('serverport');
        if (Input::get('command') == "HELO") {
            $server->host = $request->ip();
            $server->port = $serverport;
            $server->rcon = \Crypt::encrypt(Input::get('rcon'));
            $server->save();
            return "status=okay";
        }

        if (Input::get('command') == "submitshot") {
            $data = Input::get('data');
            if (!($serverport || $data)) {
                return "status=\"Error: Empty serverport or data value\"";
            } else {
                $screenshotdata = base64_decode(strtr($data, '-_#', '+/='), true);
                if ($screenshotdata != FALSE) {
                    $metadata = substr($screenshotdata, strpos($screenshotdata, "CoD4X"));

                    list($crap, $hostname, $map, $playername, $guid, $shotnum, $time) = explode("\0", $metadata);
                    $filename = $serverport . $guid . time() . ".jpg";
                    Storage::disk('screenshots')->put($filename, $screenshotdata);

                    $screenshot = new Screenshot();
                    $screenshot->filename = $filename;
                    $screenshot->guid = $guid;
                    $screenshot->name = utf8_encode($playername);
                    $screenshot->server()->associate($server);
                    //$screenshot->created_at = new Carbon($time);
                    $screenshot->save();

                    \Cache::forget('server-'.$server->id.'-players');
                    event(new ScreenshotTaken($screenshot));

                    return 'status=success';
                } else {
                    return 'status=failure';
                }
            }
        } else {
            \Log::error($request);
            $modelName = \Config::get('b3cod4x.usermodel');
            $user = $modelName::where('claimCode', $request->get('code'))->first();
            $user->guid = $request->get('player');
            $user->save();

            \Log::error($user);

            return 'status=success';
        }

    }

    public function getSearch(B3Server $server, $query = "")
    {
        return (new B3Database($server))->search($query);
    }

    public function postScreenshotAPI(B3Server $server)
    {
        $guid = Input::get('guid');
        return Cod4Server::screenshot($server, $guid);
    }

    public function getAdd()
    {

        return $this->getEdit();
    }

    public function getEdit($id = 0)
    {
        $server = B3Server::findOrNew($id);
        if ($id == 0) {
            $server->id = 0;
            $server->identifier = str_random(32);
            $server->dbSettings = [
                'host' => '127.0.0.1',
                'port' => '3306',
                'database' => 'b3',
                'username' => 'b3',
                'password' => 'password'
            ];
        } else {
            $server->dbSettings = json_decode(\Crypt::decrypt($server->dbSettings), true);
        }

        return view('b3::server.form', ['server' => $server]);
    }

    public function postSave($id = 0)
    {
        $server = B3Server::findOrNew($id);
        $server->name = Input::get('name');
        $server->identifier = Input::get('identifier');

        $db = Input::get('db');
        $server->dbSettings = \Crypt::encrypt(json_encode([
            'host' => $db['host'],
            'port' => $db['port'],
            'database' => $db['database'],
            'username' => $db['username'],
            'password' => $db['password'],
            'driver' => 'mysql',
            'engine' => null,
            'prefix' => "",
            "strict" => true,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci'
        ]));
        $server->save();

        return redirect('/b3');
    }


    public function getChat(B3Server $server)
    {
        return \Cache::remember('chat-'.$server->id,  Carbon::now()->addSecond(), function () use($server){
            $b3database = (new B3Database($server));
            $chatlogs = ChatLog::with('player')->orderBy('msg_time', 'desc')->limit(50)->get();
            $chatlogs->map(function ($chatlog, $key) {
                $date = Carbon::createFromTimestamp($chatlog->msg_time);
                $chatlog->msg_time = $date->format('d M ').$date->toTimeString();
            });
            return $chatlogs;
        });
    }

    public function postChat(B3Server $server){
        $myplayer = (new B3Database($server))->getMyPlayer();
        $b3database = (new B3Database($server));
        $message = Input::get('message');
        if($message != null) {
            $chatlog = new ChatLog();
            $chatlog->msg_time = Carbon::now()->getTimestamp();
            $chatlog->msg_type = 'ALL';
            $chatlog->msg = \Input::get('message');
            $chatlog->client_name = "(b3)" . $myplayer->name;
            $chatlog->client_team = -1;
            $chatlog->player()->associate($myplayer);
            $chatlog->save();

            $tool = new q3tool($server->host, $server->port, \Crypt::decrypt($server->rcon));
            $response = $tool->send_rcon("say ^7(^3" . $myplayer->name . "^7): ^2" . \Input::get('message'));
            \Cache::forget('chat-'.$server->id);
        }


    }



}
