// from https://www.npmjs.com/package/stringinject
function stringInject(str, data) {
    if (typeof str === "string" && data instanceof Array) {
        return str.replace(/({\d})/g, function(i) {
            return data[i.replace(/{/, "").replace(/}/, "")];
        });
    } else if (typeof str === "string" && data instanceof Object) {
        for (let key in data) {
            return str.replace(/({([^}]+)})/g, function(i) {
                let key = i.replace(/{/, "").replace(/}/, "");
                if (!data[key]) {
                    return i;
                }

                return data[key];
            });
        }
    } else {
        return false;
    }
}

const snippet = (key, namespace = null, data = null) => {
    if (window.$t[namespace] && window.$t[namespace][key]) {
        let translation = window.$t[namespace][key];
        if (data) {
            return stringInject(translation, data);
        }
        return translation;
    }
    return "";
};

const Snippet = {
    install(Vue, options) {
        Vue.snippet = snippet;
        Vue.prototype.$snippet = snippet;
    }
};
export default Snippet;
