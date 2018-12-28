<template>
    <b3serverPage id="b3admins" title="Admins" hint="Admins of server |SERVERNAME|" :serverid="serverid">
        <template slot="content">
            <table class="table table-striped jambo_table">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Group</th>
                    <th>Playername</th>
                    <th>Guid</th>
                    <th>Bans</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="admin in admins">
                    <td>{{admin.id}}</td>
                    <td>{{admin.group.name}}</td>
                    <td>{{admin.name}}</td>
                    <td>
                        <a :href="`/b3/${serverid}/player/${admin.guid}`">{{admin.guid}}</a>
                        <a :href="`https://steamcommunity.com/profiles/${admin.steamid}`" v-if="isValidSteamId(admin.steamid)">
                            <img src="/vendor/stormyy/b3cod4x/images/steam.png" width="20px">
                        </a>
                    </td>
                    <td>{{admin.totalBansGiven}}</td>
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
		data(){
			return {
				admins: [],
            }
        },
        props: {
			serverid: {required: true}
        },
        methods: {
	        isValidSteamId(){
	        	return false;
            }
        },
        created(){
	        axios.get('/b3/' + this.serverid + '/admins').then((response) => {
		        this.admins = response.data;
	        })
        }
	}
</script>

<style scoped>

</style>