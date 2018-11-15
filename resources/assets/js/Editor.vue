<template>
    <editor
        :plugins="settings.plugins"
        :toolbar="settings.toolbar"
        :init="settings"
        :initial-value="value"
        @input="changed"
    />
</template>

<script>
import tinymce from "tinymce/tinymce";
import "tinymce/themes/modern/theme";
import "tinymce/plugins/paste/plugin";
import "tinymce/plugins/link/plugin";
import "tinymce/plugins/autolink/plugin";
import "tinymce/plugins/autoresize/plugin";
import "tinymce/plugins/image/plugin";
import "tinymce/plugins/anchor/plugin";
import "tinymce/plugins/visualblocks/plugin";
import "tinymce/plugins/media/plugin";
import "tinymce/plugins/code/plugin";
import "tinymce/plugins/lists/plugin";
import "tinymce/plugins/searchreplace/plugin";
import "tinymce/plugins/contextmenu/plugin";
import "tinymce/skins/lightgray/skin.min.css";
import "tinymce/skins/lightgray/content.min.css";

import Editor from "@tinymce/tinymce-vue";
export default {
    components: {
        editor: Editor // <- Important part
    },
    props: {
        value: {
            type: String,
            default: ""
        }
    },
    data: () => ({
        settings: {
            plugins: [
                "autoresize autolink lists link image anchor",
                "searchreplace visualblocks code",
                "media contextmenu paste code"
            ],
            toolbar:
                "undo redo | eyecatcher | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code",
            theme: "modern",

            forced_root_block: "", //!important
            force_br_newlines: true, //!important
            force_p_newlines: false, //!important
            convert_urls: false,
            relative_urls: false,

            menubar: false,
            image_advtab: true
        }
    }),

    methods: {
        changed(value) {
            this.$emit("input", value);
        }
    }
};
</script>
