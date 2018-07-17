<template>
    <div>
        <div class="row">
            <div class="medium-12 columns">
              <label>
                <input type="text" v-model="searchTerm" placeholder="Suche">
              </label>
            </div>
        </div>
        <div class="row">
             <div class="medium-6 columns">
          <a class="button" v-on:click="searchTranslations">Search</a>
                <a class="button" v-on:click="clearCache">Clear Cache</a>

            </div>
        </div>
        <snippet :key="key(snippet)" :prefix="prefix" v-for="snippet in snippets" :item="snippet"></snippet>
    </div>
</template>
<script>
export default {
  props: {
    prefix: {
      type: String,
      default: ""
    }
  },
  mounted() {
    this.searchTranslations();
  },
  methods: {
    key(snippet) {
      return `${snippet.key}-${snippet.key}`;
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
  data() {
    return {
      snippets: [],
      searchTerm: ""
    };
  }
};
</script>
vc

