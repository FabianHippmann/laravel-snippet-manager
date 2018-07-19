<template>
  <div>
    <div>
      <div class="medium-12 columns">
        <div class="search-wrapper">
          <input
            type="text"
            class="search-input"
            v-model="searchTerm"
            placeholder="Fulltext search for snippet text">
          <div
            class="button"
            @click="searchTranslations">Search!</div>
        </div>
      </div>
    </div>
    <div>
      <div class="medium-6 columns">
        <a
          class="button"
          @click="clearCache">Clear Cache</a>

      </div>
    </div>
    <h4>Namespaces</h4>
    <div class="tags">
      <div
        class="tag"
        :class="{active: filter.namespace == namespace.namespace}"
        @click="selectFilter('namespace', namespace.namespace)"
        v-for="namespace in filters.namespaces"
        :key="namespace.namespace">
        {{ namespace.namespace }}
      </div>
      <div
        class="tag"
        @click="selectFilter('namespace', null)"
      >
        Reset
      </div>
    </div>
    <h4>Languages</h4>
    <div class="tags">
      <div
        class="tag"
        :class="{active: filter.locale == locale.locale}"
        @click="selectFilter('locale', locale.locale)"
        v-for="locale in filters.locales"
        :key="locale.locale">
        {{ locale.locale }}
      </div>
      <div
        class="tag"
        @click="selectFilter('locale', null)"
      >
        Reset
      </div>
    </div>
    <div class="snippet__header snippet__headerrow">
      <div class="snippet__locale">
        locale
      </div>
      <div class="snippet__key">
        key
      </div>
      <div class="snippet__namespace">
        Namespace
      </div>
      <div class="snippet__preview">
        Text
      </div>
      <div class="snippet__actions"/>
    </div>
    <snippet
      :key="key(snippet)"
      :prefix="prefix"
      v-for="snippet in filteredSnippets"
      :item="snippet"/>
  </div>
</template>
<script>
export default {
    props: {
        prefix: {
            type: String,
            default: ""
        },
        csrf: {
            type: String,
            default: null
        }
    },
     data: () => ({
        snippets: [],
        searchTerm: "",
        filters: {},
        filter: {
            locale: null,
            namespace: null
        },
        language: null,
    }),
    computed: {
        filteredSnippets(){
            let snippets = this.snippets;
            Object.keys(this.filter).forEach(key => {
                if(this.filter[key]){
                    debugger;
                    snippets = snippets.filter(snippet => (snippet[key] == this.filter[key]))
                }
            });

            return snippets;
        }
    },
    mounted() {
        if (this.csrf) {
            window.axios.defaults.headers.common["X-CSRF-TOKEN"] = this.csrf;
        }
        this.searchTranslations();
        this.getGroups();
    },
    methods: {
        key(snippet) {
            return `${snippet.key}-${snippet.key}-${snippet.locale}`;
        },
        selectFilter(type, tag){
            this.filter[type] = tag;
            debugger;
        },
        getGroups() {
            axios.get(this.prefix + "/groups").then(({data}) => {
                this.filters = data.data;
            });
        },
        searchTranslations() {
            axios
                .get(this.prefix + "/search", {
                    params: {
                        s: this.searchTerm
                    }
                })
                .then(response => {
                    this.snippets = response.data;
                });
        },
        clearCache() {
            axios.post(`${this.prefix}/clearCache`).then(response => {});
        }
    },
    components: {
        snippet: require("./Snippet.vue")
    },

};
</script>
<style scoped lang="scss" >
.tags {
  display: flex;
  margin: 20px -8px;
}
.tag {
  margin: 0 8px;
  padding: 5px;
  background-color: #333;
  color: #fff;
}
.tag.active {
  background-color: #fff;
  color: #333;
}
.search-wrapper {
  display: flex;
  align-items: center;
  margin-bottom: 16px;
}
.button {
  padding: 10px;
  background-color: #22a7f0;
  color: #fff;
}
.search-input {
  width: 100%;
  padding: 8px 8px;
}
</style>
