<?php
include_once __DIR__ . '/../../controllers/UserController.php';

$user_controller = new UserController();

class UsersView
{
    public function render_users_table()
    {
        global $user_controller;
        $users = $user_controller->get_all();

        ?>
        <div id="app">
            <main class="section">
                <p class="is-size-4 has-text-black has-text-weight-semibold pb-2">Dashboard</p>
                <template>
                    <section>
                        <b-field grouped group-multiline>
                            <div v-for="(column, index) in columnsVisible"
                                 :key="index"
                                 class="control">
                                <b-checkbox v-model="column.display">
                                    {{ column.title }}
                                </b-checkbox>
                            </div>
                        </b-field>

                        <b-table
                                :data="data"
                                :paginated="isPaginated"
                                default-sort="user_id"
                        >

                            <b-table-column field="user_id" label="ID" width="40" sortable numeric v-slot="props">
                                {{ props.row.user_id }}
                            </b-table-column>

                            <b-table-column field="last_name" label="Nume" sortable v-slot="props">
                                {{ props.row.last_name }}
                            </b-table-column>

                            <b-table-column field="first_name" label="Prenume" sortable v-slot="props">
                                {{ props.row.first_name }}
                            </b-table-column>

                            <b-table-column field="email" label="Email" sortable v-slot="props">
                                {{ props.row.email }}
                            </b-table-column>

                            <b-table-column field="sign_up_date" label="Dată înregistrare" sortable centered
                                            v-slot="props"
                                            :visible="columnsVisible['sign_up_date'].display"
                                            :label="columnsVisible['sign_up_date'].title">
                                <span class="tag is-success">
                                    {{ props.row.sign_up_date }}
                                </span>
                            </b-table-column>

                            <b-table-column field="last_online_date" label="Ultima activitate" sortable centered
                                            v-slot="props">
                                <span class="tag is-success">
                                    {{ props.row.last_online_date }}
                                </span>
                            </b-table-column>
                        </b-table>
                    </section>
                </template>
            </main>
        </div>

        <script type="application/javascript" src="https://unpkg.com/vue"></script>
        <script type="application/javascript" src="https://unpkg.com/buefy/dist/buefy.min.js"></script>
        <script type="application/javascript">
            var data = <?php echo json_encode($users) ?>

            const vueParams = {
                data() {
                    return {
                        data,
                        columnsVisible: {
                            sign_up_date: {title: 'Dată înregistrare', display: false},
                        },
                        isPaginated: false,
                        paginationPosition: 'bottom',
                        defaultSortDirection: 'asc',
                    }
                }
            }

            const app = new Vue(vueParams)
            app.$mount('#app')
        </script>
    <?php }
}