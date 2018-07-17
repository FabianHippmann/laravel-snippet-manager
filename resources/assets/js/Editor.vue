<template>
  <textarea :id="id" :value="value" class="main-content"></textarea>
</template>

<script>
import tinymce from 'tinymce';

export default {
  props : {
    id: {
      type: String,
      default: 'editor'
    },
    value: {
      type: String,
      default : ''
    }
  },
  data: function() {
    return {
      myeditor: undefined
    }
  },
  watch: {
    "value": function(newVal, oldVal){

      if(tinymce) {
        if(newVal != null && oldVal == '' && this.myeditor != null) {
          this.myeditor.setContent(newVal)
        }
      }
    }
  },
  mounted() {

    if(tinymce) {
      tinymce.init({
        selector: `#${this.id}`,
        theme: 'modern',
        setup: (editor) => {
           editor.on('init', (e) => {
                    if(this.value != undefined) editor.setContent(this.value)
                    this.$emit('input', this.value)
                })
        },
      plugins: [
        'autoresize autolink lists link image anchor',
        'searchreplace visualblocks code',
        'media contextmenu paste code'
      ],
      forced_root_block : "", //!important
      force_br_newlines : true, //!important
      force_p_newlines : false, //!important
  convert_urls: false,
    relative_urls: false,

        toolbar: 'undo redo | eyecatcher | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code',
       menubar: false,
        image_advtab: true,
        templates: [
          { title: 'Test template 1', content: 'Test 1' },
          { title: 'Test template 2', content: 'Test 2' }
        ],
        init_instance_callback: (editor) => {
          editor.on('KeyUp', (e) => {
            this.$emit('input', editor.getContent());
          });
          editor.on('Change', (e) => {
            this.$emit('input', editor.getContent());
          });

          this.myeditor = editor;
        },
      });
    }
  },
  destroyed () {
    if(tinymce) {
      this.myeditor.destroy();
    }
  }
}
</script>
