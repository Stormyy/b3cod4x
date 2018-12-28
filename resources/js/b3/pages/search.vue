<template>
    <b3serverPage id="b3search" title="Search players" hint="Search player database of server |SERVERNAME|" :serverid="serverid">
        <template slot="titleRight">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="playername" v-model="query" @change="search">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button">Go!</button>
                </span>
            </div>
        </template>
        <template slot="content">
            <table class="table table-striped jambo_table">
                <thead>
                <tr class="headings">
                    <th class="column-title">Id</th>
                    <th>Playername</th>
                    <th>Guid</th>
                    <th>Ip</th>
                    <th>Connections</th>
                    <th>Last seen</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="player in players">
                    <td>{{player.id}}</td>
                    <td v-text="player.name"></td>
                    <td>
                        <a :href="'/b3/'+serverid+'/player/'+player.guid">{{player.guid}}</a>
                        <template v-if="hasSteam(player)">
                            <a :href="getSteamUrl(player)"><img src="/vendor/stormyy/b3cod4x/images/steam.png" width="20px"></a>
                        </template>
                    </td>
                    <td v-html="player.ip"></td>
                    <td>{{player.connections}}</td>
                    <td>{{player.lastseen}}</td>
                </tr>
                </tbody>
            </table>
        </template>
    </b3serverPage>
</template>

<script>
	import serverPage from './server-page';

	export default {
		components: {
			'b3serverPage': serverPage
		},
		data() {
			return {
				query: "",
				players: []
			}
		},
		props: {
			serverid: {required: true}
		},
		watch: {
			query: function(value) {
				axios.get('/b3/' + this.serverid + '/search/' + this.query).then((response) => {
					this.players = response.data;
				})
			}
		},
		methods: {
			search() {
				axios.get('/b3/' + this.serverid + '/search/' + this.query).then((response) => {
					this.players = response.data;
				})
			},
			hasSteam(player) {
				return !(player.steamid === '' || player.steamid === 0 || player.steamid === '0');
			},
			getSteamUrl(player) {
				return 'https://steamcommunity.com/profiles/' + player.steamid;
			}
		},
        mounted(){
			this.search();
        }
	}
</script>