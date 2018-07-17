<template>
    <div class="snippet">
    	<div class="snippet__header">
            <div class="snippet__namespace">
                {{item.namespace}}/
            </div>
            <div class="snippet__key">
                {{item.key}}
            </div>
            <div class="snippet__preview">
                {{item.value | truncate(40)}}
            </div>
            <div class="snippet__locale">
                {{item.locale}}
            </div>
            <div class="snippet__actions">
                <div class="snippet__toggle" @click="toggle">Show</div>
                <div class="snippet__save" @click="save" v-show="editorToggleState && showSave">Speichern</div>
            </div>
      </div>
		<div class="snippet__body" v-if="showEditor">
          <div class="snippet__editor" v-show="editorToggleState">
              <editor :id="editorId" :value="item.value" v-on:input="updateValue"></editor>
          </div>
      </div>
    </div>
</template>

<script>
export default {
  props: ["item", "prefix"],
  computed: {
    editorId() {
      let namespace = this.item.namespace.replace(/\//g, "-");
      return `${this.item.locale}-${namespace}-${this.item.key}`;
    }
  },
  mounted() {},
  components: {
    editor: require("./Editor.vue")
  },
  methods: {
    toggle() {
      if (!this.showEditor) {
        this.showEditor = true;
      }
      this.editorToggleState = !this.editorToggleState;
    },
    save() {
      axios.put(`${this.prefix}/${this.item.id}`, this.item).then(response => {
        alert("Saved");
      });
    },
    updateValue(value) {
      this.showSave = true;
      this.item.value = value;
    }
  },
  data() {
    return {
      showEditor: false,
      editorToggleState: false,
      showSave: false
    };
  }
};
</script>

<style>
.snippet__header {
  display: flex;
  align-items: center;
  background-color: #f5f5f5;
  padding: 10px 10px;
}
.snippet__namespace {
  flex: 0 0 230px;
  color: #b7b7b7;
}
.snippet__toggle {
  padding: 5px 10px;
  border: 2px solid #b7f17e;
  cursor: pointer;
}
.snippet__locale {
  flex: 0 0 100px;
}
.snippet__preview {
  flex: 1;
}
.snippet__key {
  flex: 0 0 180px;
  height: 100%;
  word-wrap: break-word;
  font-weight: bold;
  padding: 5px 10px;
  background-color: #f5f5f5;
  /* color: #fff; */
  word-break: break-all;
}

.snippet__actions {
  justify-self: flex-end;
}

.snippet__body {
  /* padding: 20px; */
  /* background-color: #333; */
}
</style>

