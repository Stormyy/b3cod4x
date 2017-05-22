<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 22-5-2017
 * Time: 20:47
 */

namespace Stormyy\B3\Http;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Input;
use q3tool;
use Stormyy\B3\Helper\B3Database;
use Stormyy\B3\Models\B3Server;
use Stormyy\B3\Models\Penalty;

class B3PlayerController extends Controller
{
    public function get(B3Server $server, $guid)
    {
        /** @var B3Server $server */
        $b3database= (new B3Database($server));
        $player = $b3database->getUser($guid);
        \View::share('myplayer', $b3database->getMyPlayer());
        return view('b3::player.item')->with(['player' => $player, 'server' => $server, 'otherServers' => $b3database->getAllProfiles($guid)]);
    }

    public function postBan(B3Server $server, $guid, Request $request)
    {
        $this->validate($request, [
            'reason' => 'required',
            'permanent' => 'required_if:duration,null',
            'durationType' => 'required_with:duration',
            'duration' => 'required_unless:permanent,true|numeric'
        ]);

        $b3database = (new B3Database($server));
        $b3player = $b3database->getUser($guid);
        if (\Auth::user()->can('ban', [$server, $b3player])) {
            if ($request->permanent != true) {
                $minutes = 0;
                switch ($request->get('durationType')) {
                    case "minute":
                        $minutes = $request->get('duration') * 1;
                        break;
                    case "hour":
                        $minutes = $request->get('duration') * 60;
                        break;
                    case "day":
                        $minutes = $request->get('duration') * 60 * 24;
                        break;
                    case "week":
                        $minutes = $request->get('duration') * 60 * 24 * 7;
                        break;
                    case "month":
                        $minutes = $request->get('duration') * 60 * 24 * 30;
                        break;
                    case "year":
                        $minutes = $request->get('duration') * 60 * 24 * 365;
                        break;
                    default:
                        throw new \InvalidArgumentException();
                }

                $expireDate = Carbon::now()->addMinutes($minutes);
                $penalty = new Penalty();
                $penalty->type = 'TempBan';
                $penalty->player()->associate($b3player);
                $penalty->admin()->associate($b3database->getMyPlayer());
                $penalty->duration = $minutes;
                $penalty->inactive = 0;
                $penalty->reason = $request->get('reason');
                $penalty->time_add = Carbon::now()->getTimestamp();
                $penalty->time_edit = Carbon::now()->getTimestamp();
                $penalty->time_expire = $expireDate->getTimestamp();
                $penalty->save();

                $banMinutes = ($minutes > 43200 ? 43200 : $minutes);

                $tool = new q3tool($server->host, $server->port, \Crypt::decrypt($server->rcon));
                $response = $tool->send_rcon('tempban ' . $guid . " " . $banMinutes . "m" . " " . $request->get('reason'));
                return $response;
            } else {

                $penalty = new Penalty();
                $penalty->type = 'Ban';
                $penalty->player()->associate($b3player);
                $penalty->admin()->associate($b3database->getMyPlayer());
                $penalty->duration = 0;
                $penalty->inactive = 0;
                $penalty->reason = $request->get('reason');
                $penalty->time_add = Carbon::now()->getTimestamp();
                $penalty->time_edit = Carbon::now()->getTimestamp();
                $penalty->time_expire = -1;
                $penalty->save();

                $tool = new q3tool($server->host, $server->port, \Crypt::decrypt($server->rcon));
                $response = $tool->send_rcon('banclient ' . $guid . " " . $request->get('reason'));
                return $response;
            }
        } else {
            throw new AuthorizationException();
        }
    }


    public function getRemovePenalty(B3Server $server, $penaltyid)
    {
        $b3database = (new B3Database($server));
        $penalty = Penalty::findOrFail($penaltyid);
        if (\Auth::user()->can('unban', [$server, $penalty->player])) {
            $penalty->inactive = 1;
            $penalty->save();

            if ($penalty->type == 'Ban' || $penalty->type == 'TempBan') {
                $tool = new q3tool($server->host, $server->port, \Crypt::decrypt($server->rcon));
                $response = $tool->send_rcon('unban ' . $penalty->player->guid);
            }
        }

        return redirect('/b3/' . $server->id . '/player/' . $penalty->player->guid);
    }

    public function postRank(B3Server $server, $guid){
        $b3database = (new B3Database($server));
        $myplayer = $b3database->getMyPlayer();

        /** @var Player $player */
        $player = $b3database->getUser($guid);
        $rank = Input::get('rank');
        if(\Auth::user()->can('setrank', [$server, $player])) {
            if ($myplayer->group_bits > $rank || $myplayer->group_bits == 128) {
                $group = Group::findOrFail($rank);
                $player->group()->associate($group);
                $player->save();
            } else {
                throw new AuthorizationException();
            }
        } else {
            throw new AuthorizationException();
        }
    }

    public function getProfile(){
        $b3database = new B3Database(B3Server::first());
        return view('b3::profile')->with(['playsOnServers' => $b3database->getAllProfiles(\Auth::user()->guid)]);
    }

    public function getClaim()
    {
        $user = \Auth::user();
        $user->claimCode = str_random(32);
        $user->save();
        return view("b3::claim.info");
    }

}