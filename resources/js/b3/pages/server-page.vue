<template>
    <div :id="id" v-if="server">
        <div class="page-title">
            <div class="title_left">
                <h3>{{server.name}}</h3>
            </div>

            <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                    <slot name="titleRight"></slot>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>{{title}}
                            <small>{{shortHint}}</small>
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
                        <slot name="content"></slot>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
	import {B3ServerRepository} from "../../b3-server-repository";

	export default {
		data() {
			return {
				server: null
			}
		},

		computed: {
			shortHint() {
				return this.hint.replace('|SERVERNAME|', this.server.name);
			}
		},

		props: {
			id: {required: true},
			serverid: {required: true},
			title: {required: true},
			hint: {required: false}
		},

		async created() {
			this.server = await B3ServerRepository.getServer(this.serverid);
		}

	}
</script>

<style scoped>

</style>