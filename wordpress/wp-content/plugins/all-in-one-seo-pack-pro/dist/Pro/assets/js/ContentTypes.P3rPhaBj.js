import{f as v,u as x,c as T,e as j}from"./links.D18SrdNe.js";import{A,T as F}from"./TitleDescription.DbbYtFcB.js";import{C as w}from"./Card.ltM1bBnP.js";import{C as B}from"./Tabs.jOaaGWf1.js";import{C as D}from"./Tooltip.Gayc6MvE.js";import{C as O,S as P}from"./Schema.D-R88o2E.js";import{B as U}from"./Textarea.RI0SQiLK.js";import{C as q}from"./Blur.BIgRC1HX.js";import{C as L}from"./SettingsRow.BjeXKLX-.js";import{C as z}from"./Index.uAkj14B_.js";import{x as i,o as l,c as f,C as m,m as n,a as u,t as c,D as h,l as g,d as S,F as I,K as M,H as N,v as R,T as V}from"./vue.esm-bundler.DqIKZLqK.js";import{_ as y}from"./_plugin-vue_export-helper.BN1snXvA.js";import{P as E}from"./PostTypes.Cef6XkQ_.js";import{a as H}from"./index.BWln78-O.js";import"./default-i18n.Bd0Z306Z.js";import"./helpers.BOFCPzAH.js";import"./JsonValues.D25FTfEu.js";import"./MaxCounts.DHV7qSQX.js";import"./RadioToggle.olJdJ6Wy.js";import"./Caret.CE8P-qnG.js";import"./ProBadge.CjKJMApR.js";import"./RobotsMeta.RLs2O5Jw.js";import"./Checkbox.DUOJ_PLM.js";import"./Checkmark.CUxYD2Fh.js";import"./Row.DKhn_IWV.js";import"./Editor.DDwyDEN5.js";import"./Tags.C8dN262w.js";import"./postSlug.DgPEdjzX.js";import"./metabox.DtF8nuvV.js";import"./cleanForSlug.1AmsoVq6.js";import"./toString.BNLSY1cq.js";import"./_baseTrim.BYZhh0MR.js";import"./_stringToArray.DnK4tKcY.js";import"./deburr.CJsb_ehd.js";import"./get.CYs9ONpq.js";import"./GoogleSearchPreview.COiebX3i.js";import"./strings.6as-7VnG.js";import"./isString.CT51n-I9.js";import"./constants.DpuIWwJ9.js";import"./HtmlTagsEditor.C6R_toxB.js";import"./UnfilteredHtml.D65MfVHK.js";import"./Slide.Qz9BWVTI.js";import"./TruSeoScore.TjofuHRQ.js";import"./Ellipse.CwJ4-j4Z.js";import"./Information.DFP4LhAC.js";const K={components:{BaseTextarea:U,CoreBlur:q,CoreSettingsRow:L,Cta:z},props:{type:{type:String,required:!0},object:{type:Object,required:!0}},data(){return{strings:{customFields:this.$t.__("Custom Fields",this.$td),customFieldsDescription:this.$t.__("List of custom field names to include as post content for tags and the SEO Page Analysis. Add one per line.",this.$td),ctaDescription:this.$t.sprintf(this.$t.__("%1$s %2$s gives you advanced customizations for our page analysis feature, letting you add custom fields to analyze.",this.$td),"AIOSEO","Pro"),ctaButtonText:this.$t.__("Unlock Custom Fields",this.$td),ctaHeader:this.$t.sprintf(this.$t.__("Custom Fields is a %1$s Feature",this.$td),"PRO")}}},methods:{getSchemaTypeOption(t){return this.schemaTypes.find(r=>r.value===t)}}},Q={class:"aioseo-sa-ct-custom-fields lite"},W={class:"aioseo-description"};function G(t,r,e,a,s,p){const _=i("base-textarea"),d=i("core-settings-row"),b=i("core-blur"),C=i("cta");return l(),f("div",Q,[m(b,null,{default:n(()=>[m(d,{name:s.strings.customFields,align:""},{content:n(()=>[m(_,{"min-height":200}),u("div",W,c(s.strings.customFieldsDescription),1)]),_:1},8,["name"])]),_:1}),m(C,{"cta-link":t.$links.getPricingUrl("custom-fields","custom-fields-upsell",`${e.object.name}-post-type`),"button-text":s.strings.ctaButtonText,"learn-more-link":t.$links.getUpsellUrl("custom-fields",e.object.name,t.$isPro?"pricing":"liteUpgrade")},{"header-text":n(()=>[h(c(s.strings.ctaHeader),1)]),description:n(()=>[h(c(s.strings.ctaDescription),1)]),_:1},8,["cta-link","button-text","learn-more-link"])])}const J=y(K,[["render",G]]),X={setup(){return{licenseStore:v()}},components:{CustomFields:O,CustomFieldsLite:J},props:{type:{type:String,required:!0},object:{type:Object,required:!0},options:{type:Object,required:!0},showBulk:Boolean}},Y={class:"aioseo-sa-ct-custom-fields-view"};function Z(t,r,e,a,s,p){const _=i("custom-fields",!0),d=i("custom-fields-lite");return l(),f("div",Y,[a.licenseStore.isUnlicensed?S("",!0):(l(),g(_,{key:0,type:e.type,object:e.object,options:e.options,"show-bulk":e.showBulk},null,8,["type","object","options","show-bulk"])),a.licenseStore.isUnlicensed?(l(),g(d,{key:1,type:e.type,object:e.object,options:e.options,"show-bulk":e.showBulk},null,8,["type","object","options","show-bulk"])):S("",!0)])}const tt=y(X,[["render",Z]]),et={setup(){return{optionsStore:x(),rootStore:T(),settingsStore:j()}},components:{Advanced:A,CoreCard:w,CoreMainTabs:B,CoreTooltip:D,CustomFields:tt,Schema:P,SvgCircleQuestionMark:H,TitleDescription:F},mixins:[E],data(){return{internalDebounce:null,strings:{label:this.$t.__("Label:",this.$td),name:this.$t.__("Slug:",this.$td)},tabs:[{slug:"title-description",name:this.$t.__("Title & Description",this.$td),access:"aioseo_search_appearance_settings",pro:!1},{slug:"schema",name:this.$t.__("Schema Markup",this.$td),access:"aioseo_search_appearance_settings",pro:!0},{slug:"custom-fields",name:this.$t.__("Custom Fields",this.$td),access:"aioseo_search_appearance_settings",pro:!0},{slug:"advanced",name:this.$t.__("Advanced",this.$td),access:"aioseo_search_appearance_settings",pro:!1}]}},computed:{postTypes(){return this.rootStore.aioseo.postData.postTypes.filter(t=>t.name!=="attachment")}},methods:{processChangeTab(t,r){this.internalDebounce||(this.internalDebounce=!0,this.settingsStore.changeTab({slug:`${t}SA`,value:r}),setTimeout(()=>{this.internalDebounce=!1},50))},getPostIconClass(t){const r="dashicons-admin-post";return t!=null&&t.startsWith("dashicons-awb-")?r:t||r}}},st={class:"aioseo-search-appearance-content-types"},ot={class:"aioseo-description"},nt=u("br",null,null,-1),it=u("br",null,null,-1);function rt(t,r,e,a,s,p){const _=i("svg-circle-question-mark"),d=i("core-tooltip"),b=i("core-main-tabs"),C=i("core-card");return l(),f("div",st,[(l(!0),f(I,null,M(p.postTypes,(o,$)=>(l(),g(C,{key:$,slug:`${o.name}SA`},{header:n(()=>[u("div",{class:N(["icon dashicons",p.getPostIconClass(o.icon)])},null,2),h(" "+c(o.label)+" ",1),m(d,{"z-index":"99999"},{tooltip:n(()=>[u("div",ot,[h(c(s.strings.label)+" ",1),u("strong",null,c(o.label),1),nt,h(" "+c(s.strings.name)+" ",1),u("strong",null,c(o.name),1),it])]),default:n(()=>[m(_)]),_:2},1024)]),tabs:n(()=>[m(b,{tabs:s.tabs,showSaveButton:!1,active:a.settingsStore.settings.internalTabs[`${o.name}SA`],internal:"",onChanged:k=>p.processChangeTab(o.name,k)},null,8,["tabs","active","onChanged"])]),default:n(()=>[m(V,{name:"route-fade",mode:"out-in"},{default:n(()=>[(l(),g(R(a.settingsStore.settings.internalTabs[`${o.name}SA`]),{object:o,separator:a.optionsStore.options.searchAppearance.global.separator,options:a.optionsStore.dynamicOptions.searchAppearance.postTypes[o.name],type:"postTypes"},null,8,["object","separator","options"]))]),_:2},1024)]),_:2},1032,["slug"]))),128))])}const Yt=y(et,[["render",rt]]);export{Yt as default};
