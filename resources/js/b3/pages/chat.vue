<template>
    <b3serverPage id="b3chat" title="Chat" hint="All current chat messages of server |SERVERNAME|" :serverid="serverid">
        <template slot="titleRight">
            <form method="post" @submit.prevent="sendMessage" v-if="permissions.isAllowedToChat">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="my chat message.." v-model="message" maxlength="100">
                    <span class="input-group-btn">
                         <button class="btn btn-default" type="submit">Send!</button>
                       </span>
                </div>
            </form>
        </template>
        <template slot="content">
            <table class="table table-striped jambo_table">
                <thead>
                <tr>
                    <th>Time</th>
                    <th>Player</th>
                    <th>Message</th>
                    <th>Type</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="chatlog in chatlogs">
                    <td>{{chatlog.msg_time}}</td>
                    <td><a :href="'/b3/'+serverid+'/player/'+chatlog.player.guid">{{chatlog.client_name}}</a></td>
                    <td>{{chatlog.msg}}</td>
                    <td>{{chatlog.msg_type}}</td>
                </tr>
                </tbody>
            </table>
        </template>
    </b3serverPage>
</template>

<script>
	import {B3ServerPermissionRepository} from "../../b3-server-permission-repository";
	import serverPage from './server-page';

	export default {
		components: {
			'b3serverPage': serverPage
		},
		data() {
			return {
				query: "",
				permissions: {},
				chatlogs: [],
				message: "",
				interval: null,
			}
		},

		props: {
			serverid: {required: true},
		},

		methods: {
			refresh() {
				axios.get('/b3/' + this.serverid + '/chat/').then((response) => {
					this.chatlogs = response.data;
				})
			},
			sendMessage() {
				axios.post('/b3/' + this.serverid + '/chat', {'message': this.message}).then((response) => {
					this.refresh();
				});
				this.message = "";
			}
		},

		async created() {

			this.permissions = await B3ServerPermissionRepository.getPermissions(this.serverid);

			this.refresh();

			this.interval = setInterval(function() {
				this.refresh();
			}.bind(this), 1 * 1000);

		},
		beforeDestroy() {
			clearInterval(this.interval);
		}
	}
</script>