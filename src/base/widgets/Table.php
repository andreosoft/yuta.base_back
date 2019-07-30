<?php

namespace base\widgets;

class Table {

    static public function table($els = '', $columns = '', $options = '') {
        
        ?>
        <div>
            <div v-if="loading" class="loading-block">
                <div class="loader"></div>
            </div>
            <div v-if="data">
                <div class="clearfix">
                    <div class="float-right">
                        <span v-for="action in actions" style="padding-left: 4px;">
                            <template v-if="action.view == 'button'">
                                <button @click="genTopAction(action)":class="action.class" v-html="action.html" :title="action.title"></button>
                            </template>
                        </span>
                    </div>
                </div>
                <p>Показано: {{data.length}}</p>
                <hr>
                <table class="table table-bordered table-hover" style="text-align: center; vertical-align: middle;">
                    <thead>
                        <tr>
                    <template v-for="el in fields">
                        <template v-if="el.name == 'checkbox'">
                            <th><input type="checkbox" v-model="selected_all"></th>
                        </template>
                        <template v-else>
                            <th
                                @click="sortBy(el)"
                                :class="el.sort ? 'sortable' : '' "
                                >

                                {{el.title}}
                                <template v-if="el.sort">
                                    <span v-if="sort.key != el.name">
                                        <i class="fas fa-sort"></i>
                                    </span>
                                    <span v-else class="arrow" >
                                        <i class="fas" :class="sort.order == 'DESC' ? 'fa-sort-up' : 'fa-sort-down'"></i>
                                    </span>
                                </template>
                            </th>
                        </template>
                    </template>
                    </tr>
                    <tr>
                        <th v-for="el in fields">
                            <template v-if="el.filter">
                                <template v-if="el.filter.type == 'input'">
                                    <input class="form-control" type="text" @change="onChangeFilter(el)" v-model="filters[el.name]">
                                </template>
                                <template v-if="el.filter.type == 'select'">
                                    <select class="form-control" @change="onChangeModel(el)">
                                        <option v-for="(item, index) in el.filter.options" :value="index">{{item}}</option>
                                    </select>
                                </template>
                            </template>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr v-for="el in data">
                            <td v-for="attr in fields"
                                :style="attr.style ? attr.style : '' ">
                                <template v-if="attr.name == 'checkbox'">
                                    <input type="checkbox" v-model="el.selected" >
                                </template>
                                <template v-if="attr.name == 'actions'">
                                    <div v-for="action in attr.actions">
                                        <button class="btn" :class="action.class" :title="action.title" @click="genAction(action, el.id)"><i class="fas" :class="action.icon"></i></button>
                                    </div>
                                </template>
                                <template v-if="attr.render">
                                    <span v-html="attr.render(el, attr.name)"></span>
                                </template>
                                <template v-else>
                                    {{el[attr.name]}}
                                </template>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <script>
            var app = new Vue({
                el: '#app',
                data: {
                    actions: [],
                    fields: [],
                    api: '',
                    loading: false,
                    error: null,
                    selected_all: false,
                    sort: {
                        key: 'id',
                        order: 'ASC'
                    },
                    filters: {},
                    data: []
                },
                created() {
                    for (var field in this.fields) {
                        if (field.filter) {
                            this.filters[field.name] = null
                        }
                    }
                    this.fetchData()
                    for (var el of this.data) {
                        el.selected = false
                    }
                },
                watch: {
                    selected_all: function (newVal, oldVal) {
                        if (newVal) {
                            for (var el of this.data) {
                                el.selected = true
                            }
                        } else {
                            for (var el of this.data) {
                                el.selected = false
                            }
                        }
                    }
                },
                methods: {
                    onChangeFilter: function () {
                        this.push()
                    },
                    genTopAction(action) {

                        switch (action.type) {
                            case 'group':
                                var data = []
                                for (var el of this.data) {
                                    if (el.selected)
                                        data.push(el)
                                }
                                router.push({name: action.route, params: {ids: data}})
                                break;
                            default:

                        }
                    },
                    genAction(action, id) {
                        switch (action.type) {
                            case 'create':
                                router.push({name: action.path, params: {params: func.url_encode({'ret': this.$route.fullPath})}})
                                break;
                            case 'update':
                                router.push({name: action.path, params: {params: func.url_encode({'id': id, 'ret': this.$route.fullPath})}})
                            default:

                        }
                    },
                    sortBy: function (el) {
                        if (this.sort.key == el.name) {
                            if (this.sort.order == 'ASC') {
                                this.sort.order = 'DESC'
                            } else {
                                this.sort.order = 'ASC'
                            }
                        } else {
                            this.sort.key = el.name
                            this.sort.order = 'ASC'
                        }
                        this.push()
                    },
                    push: function () {
                        var params = {
                            'sort': this.sort,
                            'filters': this.filters
                        }
                        router.push({query: {q: func.url_encode(params)}})
                    },
                    fetchData() {
                        if (this.$route.query.q) {
                            var params = func.url_decode(this.$route.query.q)
                            if (params.sort) {
                                this.sort = params.sort
                            }
                            if (params.filters) {
                                this.filters = params.filters
                            }
                        }
                        this.error = null
                        this.loading = true
                        axios
                                .get(this.api, {params: params})
                                .then(response => {
                                    this.loading = false
                                    if (response.data.data == null) {
                                        this.data = []
                                    } else {
                                        this.data = response.data.data
                                    }
                                })
                                .catch(error => {
                                    console.log(error)
                                })
                    }
                },
            })
        </script>
        <?php
    }

}
