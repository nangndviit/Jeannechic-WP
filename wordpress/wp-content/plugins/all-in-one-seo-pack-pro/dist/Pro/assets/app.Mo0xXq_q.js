import"./js/vue2.Bk-dUoBE.js";import{c as a,d as i,o as c,X as l}from"./js/vue.esm-bundler.DqIKZLqK.js";import{_ as u}from"./js/_plugin-vue_export-helper.BN1snXvA.js";const p={data(){return{display:!1,interval:null}},methods:{addMenuHighlight(){const t=document.querySelector("#toplevel_page_aioseo");if(!t)return;t.querySelectorAll(".wp-submenu li").forEach(e=>{const o=e.querySelector("a");if(!o)return;const n=o.querySelector(".aioseo-menu-highlight");if(n){e.classList.add("aioseo-submenu-highlight"),n.classList.contains("red")&&e.classList.add("red");const r=e.querySelector("a");r&&n.classList.contains("lite")&&r.setAttribute("target","_blank")}})}},created(){this.addMenuHighlight()}},d={key:0};function m(t,s,e,o,n,r){return n.display?(c(),a("div",d)):i("",!0)}const _=u(p,[["render",m]]);document.getElementById("aioseo-admin")&&l({..._,name:"Standalone/App"}).mount("#aioseo-admin");
