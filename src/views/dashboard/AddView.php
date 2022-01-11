<?php

class AddView
{
    public function render($data, $metadata)
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
                            <b-field label="<?php echo $info["label"] ?>">
                                <?php if ($info["type"] === "list") { ?>
                                    <b-taginput
                                            v-model="chosenTags['<?php echo $field; ?>']"
                                            field="<?php echo $info["field_name"]; ?>"
                                            :data="filteredTags['<?php echo $field; ?>']"
                                            @typing="getFilteredTags($event, '<?php echo $field; ?>', '<?php echo $info["field_name"]; ?>')"
                                            type="is-success"
                                            icon="plus-thick"
                                            :open-on-focus="true"
                                            autocomplete
                                            ellipsis
                                            placeholder="<?php echo $info["add_label"]; ?>">
                                    </b-taginput>
                                <?php } else if ($info["type"] === "text-autocomplete") { ?>
                                    <b-autocomplete
                                            v-model="chosenTags['<?php echo $field; ?>']['<?php echo $info["field_name"]; ?>']"
                                            field="<?php echo $info["field_name"]; ?>"
                                            :data="filteredTags['<?php echo $field; ?>']"
                                            @typing="getFilteredTags($event, '<?php echo $field; ?>', '<?php echo $info["field_name"]; ?>')"
                                            @select="option => (chosenTags['<?php echo $field; ?>'] = option)"
                                            :open-on-focus="true"
                                            :clearable="true"
                                        <?php if (isset($info["required"]) && $info["required"] === true) echo " required " ?>>
                                    </b-autocomplete>
                                <?php } else { ?>
                                    <b-input
                                            id="<?php echo $field; ?>"
                                            type="<?php echo $info["type"] ?>"
                                        <?php if (isset($info["min_value_number"])) echo ' min="' . $info["min_value_number"] . '" ' ?>
                                            v-model="instance.<?php echo $field; ?>"
                                        <?php if (isset($info["required"]) && $info["required"] === true) echo " required " ?>>
                                    </b-input>
                                <?php } ?>
                            </b-field>
                        <?php } ?>

                        <input type="submit" value="Adaugă" class="button is-primary is-rounded">
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
                        filteredTags: Object.assign({}, data.all),
                        chosenTags: data.instance
                    }
                },
                methods: {
                    onSubmit() {
                        const params = {
                            source: "<?php echo $metadata["source"]; ?>",
                            action: "adauga",
                            data: this.instance
                        };
                        fetch("<?php echo $metadata["crud"]["url"];?>", {
                            method: "POST",
                            headers: {'Content-Type': 'application/json'},
                            body: JSON.stringify(params)
                        }).then(response => {
                            if (response.status !== 200) {
                                location.reload(); // Refresh page
                                throw response.text()
                            }

                            // Redirect on success
                            window.location.replace("<?php echo $metadata["crud"]["after_add_url"];?>");
                        }).catch(textPromise => textPromise.then(console.log))
                    },
                    getFilteredTags(text, obj, fieldName) {
                        this.filteredTags[obj] = data.all[obj].filter((option) => {
                            return option[fieldName]
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