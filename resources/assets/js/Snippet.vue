<template>
  <div class="snippet">
    <div class="snippet__header">
      <div class="snippet__locale">
        {{ item.locale }}
      </div>
      <div class="snippet__key">
        {{ item.key }}
      </div>
      <div class="snippet__namespace">
        {{ item.namespace }}
      </div>
      <div class="snippet__preview">
        {{ item.value | truncate(40) }}
      </div>
      <div class="snippet__actions">
        <div
          class="snippet__toggle"
          @click="toggle">Aktualisieren</div>
        <div
          class="snippet__save"
          @click="save"
          v-show="editorToggleState && showSave">Speichern</div>
      </div>
    </div>
    <div
      class="snippet__body"
      v-if="showEditor">
      <div
        class="snippet__editor"
        v-show="editorToggleState">
        <editor
          :value="item.value"
          @input="updateValue"/>
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
      return `${Math.random().toString(36).substr(2, 5)}-${this.item.locale}-${namespace}-${this.item.key}`;
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
  border-bottom: 1px solid #eaeaea;
  padding: 10px 10px;
}
.snippet__headerrow {
  background-color: #fff;
  border-bottom: 2px solid #ececec;
}
.snippet__namespace {
  flex: 0 0 120px;
  color: #b7b7b7;
}
.snippet__locale {
  flex: 0 0 50px;
}
.snippet__preview {
  flex: 1;
}
.snippet__key {
  flex: 0 0 210px;
  height: 100%;
  word-wrap: break-word;
  font-weight: bold;
  padding: 5px 10px;
  word-break: break-all;
}
.snippet__toggle,
.snippet__save {
  padding: 5px 10px;
  border: 1px solid #22a7f0;
  cursor: pointer;
  margin: 0 2.5px;
}
.snippet__save {
  border: 1px solid green;
}
.snippet__actions {
  display: flex;
  justify-self: flex-end;
}
</style>

