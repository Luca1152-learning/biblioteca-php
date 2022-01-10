<?php
include_once __DIR__ . '/../../controllers/UserController.php';

class EditView
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

                <section style="width: 60%;">
                    <form @submit.prevent="onSubmit">
                        <?php foreach ($metadata["fields"] as $field => $info) { ?>
                            <?php if ($info["type"] === "list") { ?>
                                <b-field label="<?php echo $info["label"] ?>">
                                    <b-taginput
                                            v-model="tags"
                                            field="name"
                                            :data="filteredTags"
                                            @typing="getFilteredTags"
                                            type="is-success"
                                            icon="plus-thick"
                                            autocomplete
                                            ellipsis
                                            placeholder="<?php echo $info["add_label"] ?>">
                                        <template v-slot="props">
                                            {{props.option.name}}
                                        </template>
                                    </b-taginput>
                                </b-field>
                            <?php } else { ?>
                                <b-field label="<?php echo $info["label"] ?>">
                                    <b-input
                                            id="<?php echo $field ?>"
                                            type="<?php echo $info["type"] ?>"
                                            v-model="instance.<?php echo $field; ?>"
                                        <?php if (isset($info["required"]) && $info["required"] === true) echo " required " ?>>
                                    </b-input>
                                </b-field>
                            <?php } ?>
                        <?php } ?>

                        <input type="submit" value="Modifică" class="button is-primary is-rounded">
                    </form>
                </section>
            </main>
        </div>

        <script type="application/javascript" src="https://unpkg.com/vue"></script>
        <script type="application/javascript" src="https://unpkg.com/buefy/dist/buefy.min.js"></script>
        <script type="application/javascript"
                src="https://unpkg.com/browse/buefy/dist/components/input/"></script>
        <script type="application/javascript"
                src="https://unpkg.com/browse/buefy/dist/components/autocomplete/"></script>
        <script type="application/javascript" src="https://unpkg.com/browse/buefy/dist/components/tag/"></script>
        <script type="application/javascript">

            data = <?php echo json_encode($data) ?>;
            fields = <?php echo json_encode($metadata["fields"]) ?>;

            const vueParams = {
                data() {
                    return {
                        instance: data.instance,
                        filteredTags: [data.all.authors],
                        fields,
                        tags: data.instance.authors
                    }
                },
                methods: {
                    onSubmit() {
                        console.log('Submitted');
                    },
                    getFilteredTags(text) {
                        this.filteredTags = data.all.authors.filter((option) => {
                            return option.name
                                .toString()
                                .toLowerCase()
                                .indexOf(text.toLowerCase()) >= 0
                        })
                    }
                },
            }

            const app = new Vue(vueParams)
            app.$mount('#app')
        </script>
    <?php }
}