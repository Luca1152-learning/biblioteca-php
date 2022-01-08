<?php
include_once __DIR__ . '/../../controllers/UserController.php';

class TableView
{
    public function render_table($page_title, $new_button_name, $data, $table_columns)
    {
        ?>
        <div id="app">
            <main class="section">
                <div class="is-flex is-justify-content-space-between">
                    <p class="is-size-4 has-text-black has-text-weight-semibold pb-2"><?php echo $page_title ?></p>
                    <?php if ($new_button_name != "") { ?>
                        <button class="button is-primary is-rounded"><?php echo $new_button_name ?></button>
                    <?php } ?>
                </div>
                <template>
                    <section>
                        <b-table
                                :data="data"
                                :paginated="isPaginated"
                                default-sort=<?php echo array_key_first($table_columns) ?>
                        >

                            <?php foreach ($table_columns as $field => $info) { ?>
                                <b-table-column
                                        field="<?php echo $field ?>"
                                        label="<?php echo $info["label"] ?>"
                                        v-slot="props"
                                        sortable
                                    <?php if (isset($info["centered"]) && $info["centered"] === true) echo " centered "; ?>
                                    <?php if (isset($info["width"])) echo " width=\"" . $info["width"] . "\""; ?>
                                >
                                    <?php if (isset($info["type"]) && $info["type"] === "date") { ?>
                                        <span class="tag is-success">
                                        {{ props.row.<?php echo $field ?> }}
                                    </span>
                                    <?php } else { ?>
                                        {{ props.row.<?php echo $field ?> }}
                                    <?php } ?>
                                </b-table-column>
                            <?php } ?>

                            <b-table-column field="edit" label="" v-slot="props" width="45">
                                <a href="#">
                                    <b-icon
                                            pack="fas"
                                            icon="edit"
                                            size="is-small">
                                    </b-icon>
                                </a>
                            </b-table-column>

                            <b-table-column field="delete" label="" v-slot="props" width="45">
                                <a href="#">
                                    <b-icon
                                            pack="fas"
                                            icon="trash-alt"
                                            size="is-small">
                                    </b-icon>
                                </a>
                            </b-table-column>
                        </b-table>
                    </section>
                </template>
            </main>
        </div>

        <script type="application/javascript" src="https://unpkg.com/vue"></script>
        <script type="application/javascript" src="https://unpkg.com/buefy/dist/buefy.min.js"></script>
        <script type="application/javascript">
            data = <?php echo json_encode($data) ?>;
            columns = <?php echo json_encode($table_columns) ?>;

            const vueParams = {
                data() {
                    return {
                        data,
                        columns,
                    }
                }
            }

            const app = new Vue(vueParams)
            app.$mount('#app')
        </script>
    <?php }
}