<template>
    <b3serverPage id="b3bans" title="Bans" hint="Active bans of server |SERVERNAME|" :serverid="serverid">
        <template slot="content">
            <table class="table table-striped jambo_table">
                <thead>
                <tr>
                    <th>Type</th>
                    <th>Player</th>
                    <th>Reason</th>
                    <th>Added</th>
                    <th>Admin</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <template v-for="ban in activebans.data">
                    <tr>
                        <td>{{ban.type}}</td>
                        <td><a :href="getPlayerUrl(ban.player)">{{ban.player.name}}</a></td>
                        <td>{{ban.reason}}</td>
                        <td>{{new Date(ban.time_add*1000).toISOString()}}</td>
                        <td><a :href="getPlayerUrl(ban.admin)">{{ban.admin.name}}</a></td>
                        <td><a @click="toggleBanInfo(ban)"><i class="fa fa-info-circle"></i> More info</a></td>
                    </tr>
                    <tr v-if="ban.display === true">
                        <td style="font-weight:bold">Expires at:</td>
                        <td v-text="getExpireDate(ban.time_expire)"></td>
                        <td style="font-weight:bold">Proof</td>
                        <td colspan="2" v-html="ban.proof"></td>
                    </tr>
                </template>
                </tbody>
            </table>

            <pagination :data="activebans" v-on:pagination-change-page="refresh"></pagination>
        </template>
    </b3serverPage>
</template>

<script>

	import pagination from 'laravel-vue-pagination';
	import serverPage from './server-page';

	export default {
		components: {
			'pagination': pagination,
			'b3serverPage': serverPage
		},
		data() {
			return {
				activebans: {
					data: []
				}
			}
		},

		props: {
			serverid: {required: true}
		},

		methods: {
			refresh(page = 1) {
				axios.get('/b3/' + this.serverid + '/bans?page=' + page).then((response) => {
					const bans = response.data;
					bans.data.forEach(ban => {
						ban.display = false;
						ban.proof = 'None';
                    });
					this.activebans = bans;
				})
			},
			getPlayerUrl(player) {
				return `/b3/${this.serverid}/player/${player.guid}`;
			},
            getExpireDate(timestamp){
                if(timestamp === -1){
                	return 'Never';
                }

                return new Date(timestamp*1000).toISOString();
            },
            async getProof(ban){
	            const screenshotUrl = await axios.get(`/b3/${this.serverid}/penalty/${ban.id}/screenshot`).then((response) => {
		            return response.data.url;
	            });

	            if(screenshotUrl){
	            	ban.proof = `<a href="${screenshotUrl}" data-fancybox="gallery" data-caption="${ban.player.name}"><img src="${screenshotUrl}" height="300px"></a>`;
                }
            },
			async toggleBanInfo(ban){
				await this.getProof(ban);
				ban.display = !ban.display;
            }
		},

		created() {
			this.refresh();
		}
	}
</script>

<style scoped>
 a {
     cursor: pointer;
 }
</style>