<template>
    <div id="b3servers">
        <div class="page-title">
            <div class="title_left">
                <h3>B3</h3>
            </div>

            <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="search players cross server at some point?">
                        <span class="input-group-btn">
                     <button class="btn btn-default" type="button">Go!</button>
                   </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Servers
                            <small>All b3 servers currently installed.</small>
                        </h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Settings 1</a>
                                    </li>
                                    <li><a href="#">Settings 2</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <table class="table table-striped jambo_table">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th width="50%"></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <router-link tag="tr" v-for="server in servers" :to="getServerUrl(server)" >
                                <td>{{server.name}}
                                    <a :href="'cod4://'+server.host+':'+server.port" :title="`Join ${server.name} server`">
                                        <i class="fa fa-sign-in"></i>
                                    </a>
                                </td>
                                <td>
                                    <font color="red" v-if="server.slots === null">Offline</font>
                                    <font v-else-if="server.online === -1 && server.slots === -1" color="red">Something is wrong</font>

                                    <div v-else class="progress">
                                        <div class="progress-bar progress-bar-success progress-bar-striped active"
                                             role="progressbar" :aria-valuenow="server.online"
                                             aria-valuemin="0" :aria-valuemax="server.slots"
                                             :style="`width:${Math.round((server.online/server.slots*100))}%`">
                                            {{server.online}}/{{server.slots}}
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align:right;">
                                    <router-link :to="getServerUrl(server)">View</router-link>
                                </td>
                            </router-link>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
	export default {
		data() {
			return {
				servers: []
			}
		},

		methods: {
			refreshServers() {
				axios.get('/b3/list').then((response) => {
					this.servers = response.data;
					console.log(this.servers);
				})
			},
			getServerUrl(server){
				return `/b3/${server.id}/players`;
            }
		},

		created() {
			this.refreshServers();
		}
	}
</script>

<style scoped>

</style>