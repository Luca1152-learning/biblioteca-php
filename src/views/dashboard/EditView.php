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

                <section class="operations_half_width" style="width: 50%;">
                    <form @submit.prevent="onSubmit">
                        <?php foreach ($metadata["fields"] as $field => $info) { ?>
                            <b-field label="<?php echo $info["label"] ?>" label-position="inside">
                                <b-input
                                        id="<?php echo $field ?>"
                                        type="<?php echo $info["type"] ?>"
                                    <?php if (isset($info["required"]) && $info["required"] === true) echo " required " ?>>
                                </b-input>
                            </b-field>
                        <?php } ?>

                        <input type="submit" value="Modifică" class="button is-primary is-rounded">
                    </form>
                </section>
            </main>
        </div>

        <script type="application/javascript" src="https://unpkg.com/vue"></script>
        <script type="application/javascript" src="https://unpkg.com/buefy/dist/buefy.min.js"></script>
        <script type="application/javascript">

            data = <?php echo json_encode($data) ?>;
            fields = <?php echo json_encode($metadata["fields"]) ?>;

            const vueParams = {
                data() {
                    return {
                        components: {
                            Form,
                            Field,
                        },
                        data,
                        fields,
                        methods: {
                            onSubmit() {
                                console.log('Submitted');
                            },
                        },
                    }
                }
            }

            const app = new Vue(vueParams)
            app.$mount('#app')
        </script>
    <?php }
}