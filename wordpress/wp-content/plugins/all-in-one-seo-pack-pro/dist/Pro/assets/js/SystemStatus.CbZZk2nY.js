import{c as L,v as N,D as U}from"./links.D18SrdNe.js";import{C as V}from"./Card.ltM1bBnP.js";import{G as F,a as z}from"./Row.DKhn_IWV.js";import{S as B}from"./Checkmark.CUxYD2Fh.js";import{c as j}from"./index.BWln78-O.js";import{S as O}from"./Download.DkvZRXi0.js";import{T as G,a as M}from"./Row.CkLy7DTq.js";import{x as e,W as J,c as r,C as s,m as o,o as n,a,D as u,t as l,N as W,l as k,F as d,d as f,H as g,K as I}from"./vue.esm-bundler.DqIKZLqK.js";import{_ as H}from"./_plugin-vue_export-helper.BN1snXvA.js";import"./default-i18n.Bd0Z306Z.js";import"./helpers.BOFCPzAH.js";import"./Tooltip.Gayc6MvE.js";import"./Caret.CE8P-qnG.js";import"./Slide.Qz9BWVTI.js";const K={setup(){return{rootStore:L(),toolsStore:N()}},components:{CoreCard:V,GridColumn:F,GridRow:z,SvgCheckmark:B,SvgCopy:j,SvgDownload:O,TableColumn:G,TableRow:M},data(){return{copied:!1,emailError:null,emailAddress:null,sendingEmail:!1,strings:{systemStatusInfo:this.$t.__("System Status Info",this.$td),downloadSystemInfoFile:this.$t.__("Download System Info File",this.$td),copyToClipboard:this.$t.__("Copy to Clipboard",this.$td),emailDebugInformation:this.$t.__("Email Debug Information",this.$td),submit:this.$t.__("Submit",this.$td),wordPress:this.$t.__("WordPress",this.$td),serverInfo:this.$t.__("Server Info",this.$td),activeTheme:this.$t.__("Active Theme",this.$td),muPlugins:this.$t.__("Must-Use Plugins",this.$td),activePlugins:this.$t.__("Active Plugins",this.$td),inactivePlugins:this.$t.__("Inactive Plugins",this.$td),copied:this.$t.__("Copied!",this.$td)}}},computed:{copySystemStatusInfo(){return JSON.stringify(this.rootStore.aioseo.data.status)}},methods:{onCopy(){this.copied=!0,setTimeout(()=>{this.copied=!1},2e3)},onError(){},downloadSystemStatusInfo(){const y=new Blob([JSON.stringify(this.rootStore.aioseo.data.status)],{type:"application/json"}),i=document.createElement("a");i.href=URL.createObjectURL(y),i.download=`aioseo-system-status-${U.now().toFormat("yyyy-MM-dd")}.json`,i.click(),URL.revokeObjectURL(i.href)},processEmailDebugInfo(){if(this.emailError=!1,!this.emailAddress||!/^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(this.emailAddress)){this.emailError=!0;return}this.sendingEmail=!0,this.toolsStore.emailDebugInfo(this.emailAddress).then(()=>{this.emailAddress=null,this.sendingEmail=!1})}}},q={class:"aioseo-tools-system-status-info"},Q={class:"actions"},X={class:"aioseo-settings-row"},Y={class:"settings-name"},Z={class:"name"},tt={class:"settings-content"},st={class:"system-status-table"},et=["title"];function ot(y,i,nt,E,t,c){const D=e("svg-download"),_=e("base-button"),A=e("svg-copy"),$=e("svg-checkmark"),b=e("grid-column"),T=e("base-input"),x=e("grid-row"),S=e("table-column"),P=e("table-row"),R=e("core-card"),p=J("clipboard");return n(),r("div",q,[s(R,{slug:"systemStatusInfo","header-text":t.strings.systemStatusInfo},{default:o(()=>[a("div",Q,[s(x,null,{default:o(()=>[s(b,{sm:"6",class:"left"},{default:o(()=>[s(_,{type:"blue",size:"small",onClick:c.downloadSystemStatusInfo},{default:o(()=>[s(D),u(" "+l(t.strings.downloadSystemInfoFile),1)]),_:1},8,["onClick"]),W((n(),k(_,{type:"blue",size:"small"},{default:o(()=>[t.copied?f("",!0):(n(),r(d,{key:0},[s(A),u(" "+l(t.strings.copyToClipboard),1)],64)),t.copied?(n(),r(d,{key:1},[s($),u(" "+l(t.strings.copied),1)],64)):f("",!0)]),_:1})),[[p,c.copySystemStatusInfo,"copy"],[p,c.onCopy,"success"],[p,c.onError,"error"]])]),_:1}),s(b,{sm:"6",class:"right"},{default:o(()=>[s(T,{size:"small",placeholder:t.strings.emailDebugInformation,modelValue:t.emailAddress,"onUpdate:modelValue":i[0]||(i[0]=m=>t.emailAddress=m),class:g({"aioseo-error":t.emailError})},null,8,["placeholder","modelValue","class"]),s(_,{type:"blue",size:"small",onClick:c.processEmailDebugInfo,loading:t.sendingEmail},{default:o(()=>[u(l(t.strings.submit),1)]),_:1},8,["onClick","loading"])]),_:1})]),_:1})]),a("div",X,[(n(!0),r(d,null,I(E.rootStore.aioseo.data.status,(m,v)=>{var w;return n(),r(d,{key:v},[(w=m.results)!=null&&w.length?(n(),r("div",{key:0,class:g(["settings-group",["settings-group--"+v]])},[a("div",Y,[a("div",Z,l(m.label),1)]),a("div",tt,[a("div",st,[(n(!0),r(d,null,I(m.results,(h,C)=>(n(),k(P,{key:C,class:g({even:C%2===0})},{default:o(()=>[s(S,{class:"system-status-header"},{default:o(()=>[a("span",{title:h.header},l(h.header),9,et)]),_:2},1024),s(S,null,{default:o(()=>[u(l(h.value),1)]),_:2},1024)]),_:2},1032,["class"]))),128))])])],2)):f("",!0)],64)}),128))])]),_:1},8,["header-text"])])}const bt=H(K,[["render",ot]]);export{bt as default};
