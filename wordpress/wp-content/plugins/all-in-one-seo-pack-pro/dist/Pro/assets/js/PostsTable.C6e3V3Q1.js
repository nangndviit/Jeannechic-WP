import{D as $,f as C,G as I,e as A,u as j}from"./links.D18SrdNe.js";import{l as O}from"./license.CZ_ULMsy.js";import{n as x}from"./numbers.zAmItkHM.js";import{W as L}from"./WpTable.yjBoG_Qq.js";import{_ as R}from"./ScoreButton.BYXILR6h.js";import{C as U}from"./Table.DPPHow5O.js";import{C as D}from"./Index.uAkj14B_.js";import{q as B,S as E,T as N,c as V}from"./LicenseConditions.m3GuYfLc.js";import{x as c,o as d,c as h,C as p,t as r,d as u,a as o,m as l,G as H,D as _,l as b}from"./vue.esm-bundler.DqIKZLqK.js";import{_ as v}from"./_plugin-vue_export-helper.BN1snXvA.js";import{_ as M}from"./IndexStatus.UMK1d_xE.js";import{a as q}from"./addons.DR5v859-.js";import{S as W,a as G,b as Z,c as z}from"./Affiliate.CZtLUjwg.js";import{S as J}from"./Suggestion.DZXjrRRX.js";import{P as K}from"./PostTypes.Cef6XkQ_.js";const Q={components:{apexchart:B},props:{points:{type:Object,required:!0},peak:{type:Number,default(){return 0}},recovering:{type:Boolean,default(){return!1}},height:{type:Number,default(){return 50}}},data(){return{strings:{recovering:this.$t.__("Slowly Recovering",this.$td),peak:this.$t.__("Peak",this.$td)}}},computed:{getSeries(){const t=this.points,n=[];return Object.keys(t).forEach(s=>{n.push({x:s,y:t[s]})}),[{data:n}]},chartOptions(){const t=this.peak;return{colors:[function({value:n}){return n===t?"#005AE0":"#99C2FF"}],chart:{type:"bar",sparkline:{enabled:!0},zoom:{enabled:!1},toolbar:{show:!1},parentHeightOffset:0,background:"#fff"},grid:{show:!1,padding:{top:2,right:2,bottom:0,left:2}},plotOptions:{bar:{columnWidth:"85%",barHeight:"100%"}},fill:{type:"solid"},tooltip:{enabled:!0,x:{show:!0,formatter:n=>$.fromFormat(n,"yyyy-MM").setZone($.zone).toLocaleString({month:"long",year:"numeric"})},y:{formatter:n=>{const s=this.$t.sprintf(this.$t.__("%1$s points",this.$td),x.numberFormat(n,0));let a="";return n===t&&(a=`<span class="peak">${this.strings.peak}</span>`),s+a}},marker:{show:!1}}}}}},X={class:"aioseo-graph-decay"},Y={key:0,class:"aioseo-graph-decay-recovering"};function tt(t,n,s,a,i,g){const m=c("apexchart");return d(),h("div",X,[p(m,{width:"100%",height:s.height,ref:"apexchart",options:g.chartOptions,series:g.getSeries,class:"aioseo-graph-decay-chart"},null,8,["height","options","series"]),s.recovering?(d(),h("div",Y,r(i.strings.recovering),1)):u("",!0)])}const et=v(Q,[["render",tt]]),st={components:{SvgLinkAffiliate:W,SvgLinkExternal:G,SvgLinkInternalInbound:Z,SvgLinkInternalOutbound:z,SvgLinkSuggestion:J},props:{row:Object},data(){return{addons:q,strings:{links:this.$t.__("Links:",this.$tdPro)}}}},it={key:0,class:"object-actions"},ot={key:0,class:"link-assistant"},nt={class:"title"},at={class:"count"},rt={class:"total"},lt={class:"count"},ct={class:"total"},dt={class:"count"},pt={class:"total"},ut={class:"count"},ht={class:"total"},ft={class:"count"},_t={class:"total"};function gt(t,n,s,a,i,g){const m=c("svg-link-internal-inbound"),k=c("svg-link-internal-outbound"),S=c("svg-link-external"),f=c("svg-link-affiliate"),y=c("svg-link-suggestion");return s.row.objectId?(d(),h("div",it,[i.addons.isActive("aioseo-link-assistant")&&!i.addons.requiresUpgrade("aioseo-link-assistant")&&s.row.linkAssistant?(d(),h("div",ot,[o("div",nt,r(i.strings.links),1),o("div",at,[p(m),o("span",rt,r(s.row.linkAssistant.inboundInternal||0),1)]),o("div",lt,[p(k),o("span",ct,r(s.row.linkAssistant.outboundInternal||0),1)]),o("div",dt,[p(S),o("span",pt,r(s.row.linkAssistant.external||0),1)]),o("div",ut,[p(f),o("span",ht,r(s.row.linkAssistant.affiliate||0),1)]),o("div",ft,[p(y),o("span",_t,r(s.row.linkAssistant.linkSuggestions||0),1)])])):u("",!0)])):u("",!0)}const mt=v(st,[["render",gt]]),bt={setup(){return{licenseStore:C(),searchStatisticsStore:I(),settingsStore:A(),optionsStore:j()}},components:{CoreScoreButton:R,CoreWpTable:U,Cta:D,GraphDecay:et,IndexStatus:M,ObjectActions:mt,Statistic:E},mixins:[K,L,N],data(){return{numbers:x,tableId:"aioseo-search-statistics-post-table",changeItemsPerPageSlug:"searchStatisticsSeoStatistics",showUpsell:!1,sortableColumns:[],strings:{position:this.$t.__("Position",this.$td),ctaButtonText:this.$t.__("Unlock Post Tracking",this.$td),ctaHeader:this.$t.sprintf(this.$t.__("Post Tracking is a %1$s Feature",this.$td),"PRO")},license:O}},props:{posts:Object,isLoading:Boolean,showHeader:{type:Boolean,default(){return!0}},showTableFooter:Boolean,showItemsPerPage:Boolean,columns:{type:Array,default(){return["postTitle","seoScore","clicks","impressions","position"]}},appendColumns:{type:Object,default(){return{}}},defaultSorting:{type:Object,default(){return{}}},initialFilter:{type:String,default(){return""}},updateAction:{type:String,default(){return"updateSeoStatistics"}}},computed:{allColumns(){var s,a;const t=V(this.columns),n=((a=(s=this.posts)==null?void 0:s.filters)==null?void 0:a.find(i=>i.active))||{};return this.appendColumns[n.slug||"all"]&&t.push(this.appendColumns[n.slug||"all"]),t.map(i=>(i.endsWith("Sortable")&&(i=i.replace("Sortable",""),this.sortableColumns.push(i)),i))},tableColumns(){return[{slug:"row",label:"#",width:"40px"},{slug:"postTitle",label:this.$t.__("Title",this.$td),width:"100%"},{slug:"seoScore",label:this.$t.__("TruSEO Score",this.$td),width:"130px"},{slug:"indexStatus",label:this.$t.__("Indexed",this.$td),width:"80px",coreFeature:"index-status"},{slug:"clicks",label:this.$t.__("Clicks",this.$td),width:"80px"},{slug:"impressions",label:this.$t.__("Impressions",this.$td),width:"110px"},{slug:"position",label:this.$t.__("Position",this.$td),width:"90px"},{slug:"lastUpdated",label:this.$t.__("Last Updated On",this.$td),width:"160px"},{slug:"decay",label:this.$t.__("Loss",this.$td),width:"140px"},{slug:"decayPercent",label:this.$t.__("Drop (%)",this.$td),width:"120px"},{slug:"performance",label:this.$t.__("Performance Score",this.$td),width:"150px"},{slug:"diffDecay",label:this.$t.__("Diff",this.$td),width:"95px"},{slug:"diffPosition",label:this.$t.__("Diff",this.$td),width:"80px"}].filter(t=>t.coreFeature&&(!this.$isPro||this.licenseStore.isUnlicensed||!this.license.hasCoreFeature("search-statistics",t.coreFeature))?!1:t.slug==="seoScore"?this.optionsStore.options.advanced.truSeo:this.allColumns.includes(t.slug)).map(t=>(t.sortable=this.isSortable&&this.sortableColumns.includes(t.slug),t.sortable&&(t.sortDir=t.slug===this.orderBy?this.orderDir:"asc",t.sorted=t.slug===this.orderBy),t))},isSortable(){return this.filter==="all"&&this.$isPro&&!this.licenseStore.isUnlicensed}},watch:{isLoading(t){t||this.$nextTick(()=>{this.loadInspectionResult()})}},methods:{resetSelectedFilters(){this.selectedFilters.postType="",this.processAdditionaFilterOptionSelected({name:"postType",selectedValue:""})},fetchData(t){if(typeof this.searchStatisticsStore[this.updateAction]=="function")return this.searchStatisticsStore[this.updateAction](t)},loadInspectionResult(){var n;if(!((n=this.posts)!=null&&n.rows)||this.searchStatisticsStore.quotaExceeded.urlInspection)return;const t=Object.values(this.posts.rows).filter(s=>{var a;return!s.inspectionResult||((a=s.inspectionResult)==null?void 0:a.length)===0});t.length&&(t.forEach(s=>{this.posts.rows[s.page].inspectionResultLoading=!0}),this.searchStatisticsStore.getInspectionResult(t.map(s=>s.page)).then(s=>{t.forEach(a=>{this.posts.rows[a.page].inspectionResult=s[a.page],this.posts.rows[a.page].inspectionResultLoading=!1})}))}},mounted(){this.initialFilter&&this.processFilter({slug:this.initialFilter}),this.loadInspectionResult()}},kt={class:"aioseo-search-statistics-post-table"},St={class:"object-row"},yt={class:"object-title"},wt=["onClick"],vt={key:1,class:"object-title"},Pt={key:0,class:"row-actions"},$t=["href"],xt=["href"];function Ft(t,n,s,a,i,g){const m=c("object-actions"),k=c("core-score-button"),S=c("index-status"),f=c("statistic"),y=c("graph-decay"),F=c("cta"),T=c("core-wp-table");return d(),h("div",kt,[p(T,{ref:"table",class:"posts-table",id:i.tableId,columns:g.tableColumns,rows:Object.values(s.posts.rows),totals:s.posts.totals,filters:s.posts.filters,"additional-filters":s.posts.additionalFilters,"selected-filters":t.selectedFilters,loading:s.isLoading,"initial-page-number":t.pageNumber,"initial-search-term":t.searchTerm,"initial-items-per-page":a.settingsStore.settings.tablePagination[i.changeItemsPerPageSlug],"show-header":s.showHeader,"show-bulk-actions":!1,"show-table-footer":s.showTableFooter,"show-items-per-page":s.showItemsPerPage,"show-pagination":"","blur-rows":i.showUpsell,onFilterTable:t.processFilter,onProcessAdditionalFilters:t.processAdditionalFilters,onAdditionalFilterOptionSelected:t.processAdditionaFilterOptionSelected,onPaginate:t.processPagination,onProcessChangeItemsPerPage:t.processChangeItemsPerPage,onSearch:t.processSearch,onSortColumn:t.processSort},{row:l(({index:e})=>[o("div",St,r(e+1),1)]),postTitle:l(({row:e})=>[o("div",yt,[e.objectId&&e.objectType==="post"?(d(),h("a",{key:0,href:"#",onClick:H(w=>t.openPostDetail(e),["prevent"])},r(e.objectTitle),9,wt)):(d(),h("span",vt,r(e.objectTitle),1))]),p(m,{row:e},null,8,["row"]),e.objectId&&e.objectType==="post"?(d(),h("div",Pt,[o("span",null,[o("a",{class:"view",href:e.context.permalink,target:"_blank"},[o("span",null,r(t.viewPost(e.context.postType.singular)),1)],8,$t),_(" | ")]),o("span",null,[o("a",{class:"edit",href:e.context.editLink,target:"_blank"},[o("span",null,r(t.editPost(e.context.postType.singular)),1)],8,xt)])])):u("",!0)]),seoScore:l(({row:e})=>[e.seoScore?(d(),b(k,{key:0,class:"table-score-button",score:e.seoScore},null,8,["score"])):u("",!0)]),indexStatus:l(({row:e})=>{var w,P;return[p(S,{result:(w=e.inspectionResult)==null?void 0:w.indexStatusResult,"result-link":(P=e.inspectionResult)==null?void 0:P.inspectionResultLink,loading:e.inspectionResultLoading},null,8,["result","result-link","loading"])]}),clicks:l(({row:e})=>[_(r(i.numbers.compactNumber(e.clicks)),1)]),impressions:l(({row:e})=>[_(r(i.numbers.compactNumber(e.impressions)),1)]),position:l(({row:e})=>[_(r(Math.round(e.position).toFixed(0)),1)]),lastUpdated:l(({row:e})=>[_(r(e.context.lastUpdated||"-"),1)]),decay:l(({row:e})=>[p(f,{type:"decay","show-difference":!1,total:e.decay,showZeroValues:!0,class:"no-margin"},null,8,["total"])]),decayPercent:l(({row:e})=>[p(f,{type:"decayPercent","show-difference":!1,total:e.decayPercent,showZeroValues:!0,class:"no-margin"},null,8,["total"])]),performance:l(({row:e})=>[p(y,{points:e.points,peak:e.peak,recovering:e.recovering,height:38},null,8,["points","peak","recovering"])]),diffPosition:l(({row:e})=>[e.difference.comparison?(d(),b(f,{key:0,type:"position","show-original":!1,difference:e.difference.position,"tooltip-offset":"-100px,0"},null,8,["difference"])):u("",!0)]),diffDecay:l(({row:e})=>[e.difference.comparison?(d(),b(f,{key:0,type:"diffDecay","show-original":!1,difference:e.difference.decay,"tooltip-offset":"-100px,0"},null,8,["difference"])):u("",!0)]),cta:l(()=>[i.showUpsell?(d(),b(F,{key:0,"cta-link":t.$links.getPricingUrl("search-statistics","search-statistics-upsell"),"button-text":i.strings.ctaButtonText,"learn-more-link":t.$links.getUpsellUrl("search-statistics","search-statistics-upsell",t.$isPro?"pricing":"liteUpgrade"),"hide-bonus":!a.licenseStore.isUnlicensed},{"header-text":l(()=>[_(r(i.strings.ctaHeader),1)]),_:1},8,["cta-link","button-text","learn-more-link","hide-bonus"])):u("",!0)]),_:1},8,["id","columns","rows","totals","filters","additional-filters","selected-filters","loading","initial-page-number","initial-search-term","initial-items-per-page","show-header","show-table-footer","show-items-per-page","blur-rows","onFilterTable","onProcessAdditionalFilters","onAdditionalFilterOptionSelected","onPaginate","onProcessChangeItemsPerPage","onSearch","onSortColumn"])])}const Mt=v(bt,[["render",Ft]]);export{Mt as P};