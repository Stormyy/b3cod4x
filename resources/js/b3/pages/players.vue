<template>
    <b3serverPage id="b3players" title="Current players" hint="Current players playing in server |SERVERNAME|" :serverid="serverid">
        <template slot="titleRight">
            <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                <h3 v-text="refreshTimerText" style="display: block; margin: 0; text-align: right"></h3>
            </div>
        </template>
        <template slot="content">
            <table class="table table-striped jambo_table">
                <thead>
                <tr class="headings">
                    <th class="column-title visible-md visible-lg">Id</th>
                    <th>Playername</th>
                    <th class="visible-md visible-lg">Guid</th>
                    <th class="visible-md visible-lg">Ip</th>
                    <th class="visible-md visible-lg">Screenshots</th>
                    <th>
                        <mq-layout mq="mobile">
                            SS
                        </mq-layout>
                        <mq-layout mq="tablet+">
                            Latest screenshot
                        </mq-layout>
                    </th>
                    <th v-if="permissions.isAllowedToScreenshot"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="player in players" :style="cssClass(player)">
                    <td class="visible-md visible-lg">{{player.DBID}}</td>
                    <td>
                        <a :href="'/b3/'+serverid+'/player/'+player.GUID">{{player.Name}}</a>
                        <span class="visible-sm visible-xs">
                            <template v-if="hasSteam(player)">
                                <a :href="getSteamUrl(player)" target="_blank"><img
                                        src="/vendor/stormyy/b3cod4x/images/steam.png" width="20px"></a>
                            </template>
                        </span>
                    </td>
                    <td class="visible-md visible-lg">
                        <a :href="'/b3/'+serverid+'/player/'+player.GUID">{{player.GUID}}</a>
                        <template v-if="hasSteam(player)">
                            <a :href="getSteamUrl(player)" target="_blank"><img
                                    src="/vendor/stormyy/b3cod4x/images/steam.png" width="20px"></a>
                        </template>
                    </td>
                    <td v-html="player.IP" class="visible-md visible-lg"></td>
                    <td class="visible-md visible-lg">{{player.screenshots.length}}</td>
                    <td v-html="latestScreenshot(player)"></td>
                    <td v-if="permissions.isAllowedToScreenshot === true">
                        <button @click="postScreenshot(player)" class="btn btn-primary">
                            <mq-layout mq="mobile">
                                <img src="/vendor/stormyy/b3cod4x/images/screenshot.png" width="30px">
                            </mq-layout>
                            <mq-layout mq="tablet+">
                                Screenshot
                            </mq-layout>
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </template>
    </b3serverPage>
</template>

<script>
	import {B3ServerPermissionRepository} from "../../b3-server-permission-repository";
	import serverPage from './server-page';

	const moment = require('moment-timezone');
	window['moment'] = moment;

	export default {
		components: {
			'b3serverPage': serverPage
		},
		data() {
			return {
				players: [],
				permissions: {},
				interval: null,
				timer: 0,
				refreshTime: 10
			}
		},

		props: {
			serverid: {required: true},
		},

		computed: {
			refreshTimerText() {
				if (this.timer > 0) {
					return `Refreshing in ${this.timer}..`;
				}

				return 'Refreshing...';
			}
		},

		methods: {
			latestScreenshot(player) {
				let latestScreenshot = player.screenshots[0];
				if (latestScreenshot !== undefined) {
					let currentDate = moment(moment().tz('Europe/Amsterdam').add(5, 'seconds').format('YYYY-MM-DD HH:mm'));
					return '<a href="' + latestScreenshot.url + '" data-fancybox="gallery" data-caption="' + player.Name + '">' + moment(latestScreenshot.created_at).from(currentDate) + '</a>';
				}
			},
			reloadPlayers(callback = null) {
				axios.get('/b3/' + this.serverid + '/player').then(response => {
					this.players = response.data.players;

					if (callback !== null) {
						callback(this);
					}

				});

				this.timer = this.refreshTime;
			},
			postScreenshot(player) {
				axios.post('/b3/' + this.serverid + '/screenshot/api', {"guid": player.GUID}).then(response => {
					swal("Screenshot", "Screenshot request send for " + player.Name + "\n" + response.data.split('print')[1], "success");
				}).catch(error => {
					swal("Screenshot", error.response.data.message, "error");
				});
			},
			cssClass(player) {
				if (player.bannedOnOtherServers === true) {
					return 'background-color:#ff6d6b; color:white;'
				}
				return '';
			},
			hasSteam(player) {
				return !(player.steamid === '' || player.steamid === 0 || player.steamid === '0');
			},
			getSteamUrl(player) {
				return 'https://steamcommunity.com/profiles/' + player.steamid;
			}
		},
		async created() {
			this.permissions = await B3ServerPermissionRepository.getPermissions(this.serverid);

			this.reloadPlayers();
			this.interval = setInterval(function() {

				if (this.timer < 0) {
					this.reloadPlayers();
				} else {
					this.timer -= 1;
				}

			}.bind(this), 1 * 1000);

			if (this.$echo.options.key !== "" && this.permissions.isAllowedToScreenshot) {
				this.$echo.channel('screenshots.' + this.serverid).listen('ScreenshotTaken', (data) => {
					this.reloadPlayers(() => {
						let msg = "Screenshot of player " + data.screenshot.name + " has been uploaded";
						if (data.takenBy !== null) {
							msg = `Screenshot of player ${data.screenshot.name} taken by ${data.takenBy.name} has been uploaded`
						}

						this.$toastr.Add({
							title: "Screenshot has been taken", // Toast Title
							msg: msg, // Message
							clickClose: true, // Click Close Disable
							closeOnHover: true,
							timeout: 5 * 60 * 1000, // Remember defaultTimeout is 5 sec..
							position: "toast-top-full-width", // Toast Position.
							type: "success" // Toast type
						});
					})
				});
			}
		},

		beforeDestroy() {
			if (this.interval) {
				clearInterval(this.interval);
				this.interval = null;
			}
		},
	}
</script>