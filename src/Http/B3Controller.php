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
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Stormyy\B3\Events\ScreenshotTaken;
use Stormyy\B3\Helper\B3Database;
use Stormyy\B3\Helper\PermissionHelper;
use Stormyy\B3\Models\B3Server;
use Stormyy\B3\Models\Screenshot;
use q3tool;
use Storage;
use Illuminate\Support\Facades\Input;

class B3Controller extends Controller
{
    public function __construct()
    {

    }

    public function getList()
    {
        return view('b3::server.list')->with(['servers' => B3Server::get()]);
    }

    public function getPlayer($id, $guid)
    {
        /** @var Server $server */
        $server = B3Server::findOrFail($id);
        $player = (new B3Database($server))->getUser($guid);
        return view('b3::player.item')->with(['player' => $player]);
    }

    public function getScreenshotList($id)
    {
        /** @var Server $server */
        $server = B3Server::findOrFail($id);
        $data = ['server' => $server];
        return view('b3::player.list')->with($data);
    }

    public function getCurrentPlayers($id)
    {
        /** @var Server $server */
        $server = B3Server::findOrFail($id);
        $b3users = (new B3Database($server))->getCurrentClients();
        $b3CurrentUserList = [];
        foreach ($b3users as $b3user) {

            $b3user->screenshots = $server->screenshots()->where('guid', $b3user->GUID)->orderBy('created_at', 'desc')->get();;
            $b3user->IP = PermissionHelper::ip($b3user->IP);
            $b3user->AllowScreenshot = \Auth::check();
            $b3CurrentUserList[] = $b3user;
        }

        return $b3CurrentUserList;
    }

    public function postScreenshot(Request $request)
    {

        $identkey = Input::get('identkey');
        $server = B3Server::where('identifier', $identkey)->first();
        $serverport = Input::get('serverport');
        if (Input::get('command') == "HELO") {
            $server->host = $request->ip();
            $server->port = $serverport;
            $server->rcon = Input::get('rcon');
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
                    $screenshot->name = $playername;
                    $screenshot->server()->associate($server);
                    //$screenshot->created_at = new Carbon($time);
                    $screenshot->save();

                    event(new ScreenshotTaken($screenshot));

                    return 'status=success';
                } else {
                    return 'status=failure';
                }
            }
        }

    }

    public function getSearch($id, $query = "")
    {
        $server = B3Server::findOrFail($id);
        return (new B3Database($server))->search($query);
    }

    public function postScreenshotAPI($id)
    {
        $server = B3Server::findOrFail($id);
        $guid = Input::get('guid');

        $tool = new q3tool($server->host, $server->port, $server->rcon);
        $response = $tool->send_rcon('getss ' . $guid);
        return $response;
    }

    public function getAdd(){

        return $this->getEdit();
    }

    public function getEdit($id=0){
        $server = B3Server::findOrNew($id);
        $server->identifier = str_random(32);
        if($id == 0){
            $server->id = 0;
            $server->dbSettings = [
                'host' => '127.0.0.1',
                'port' => '3306',
                'database' => 'b3',
                'username' => 'b3',
                'password' => 'password'
            ];
        }

        return view('b3::server.form', ['server' => $server]);
    }

    public function postSave($id=0){
        $server = B3Server::findOrNew($id);
        $server->name = Input::get('name');
        $server->identifier = Input::get('identifier');

        $db = Input::get('db');
        $server->dbSettings = [
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
        ];
        $server->save();

        return redirect('/b3');
    }


}
