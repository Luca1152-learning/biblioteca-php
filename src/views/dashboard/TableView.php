<?php
include_once __DIR__ . '/../../controllers/UserController.php';

class TableView
{
    public function render_table($data, $metadata)
    {
        ?>
        <div id="app">
            <main class="section">
                <div class="is-flex is-justify-content-space-between">
                    <p class="is-size-4 has-text-black has-text-weight-semibold pb-2"><?php echo $metadata["page_title"] ?></p>
                    <?php if (isset($metadata["new_button_label"])) { ?>
                        <button class="button is-primary is-rounded"><?php echo $metadata["new_button_label"] ?></button>
                    <?php } ?>
                </div>
                <template>
                    <section>
                        <b-table
                                :data="data"
                                default-sort=<?php echo array_key_first($metadata["columns"]) ?>
                        >

                            <?php foreach ($metadata["columns"] as $field => $info) { ?>
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
                                    <?php } else if (isset($info["type"]) && $info["type"] === "list") { ?>
                                        <b-taglist>
                                            <b-tag v-for="item in props.row.<?php echo $field; ?>.slice(0,2)"
                                                   :key="index" type="is-success">
                                                {{item.name}}
                                            </b-tag>
                                            <b-tag v-if="props.row.<?php echo $field; ?>.length > 2"
                                                   :key="index" type="is-success">
                                                ...
                                            </b-tag>
                                        </b-taglist>
                                    <?php } else { ?>
                                        {{ props.row.<?php echo $field ?> }}
                                    <?php } ?>
                                </b-table-column>
                            <?php } ?>

                            <b-table-column field="edit" label="" v-slot="props" width="45">
                                <a :href="`<?php echo $metadata["modify_url"] ?>${props.row.<?php echo array_key_first($metadata["columns"]) ?>}`">
                                    <b-icon
                                            pack="fas"
                                            icon="edit"
                                            size="is-small">
                                    </b-icon>
                                </a>
                            </b-table-column>

                            <b-table-column field="delete" label="" v-slot="props" width="45">
                                <a :href="`<?php echo $metadata["delete_url"] ?>`">
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
            columns = <?php echo json_encode($metadata["columns"]) ?>;

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