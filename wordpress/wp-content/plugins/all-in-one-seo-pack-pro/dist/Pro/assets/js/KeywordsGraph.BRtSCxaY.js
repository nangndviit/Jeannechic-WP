import{G as g,f}from"./links.D18SrdNe.js";import{G as $}from"./SeoStatisticsOverview.E9u2we1n.js";import{x as i,o as c,c as h,C as p,l as _,m as u,a as w,D as S,t as k,d as m}from"./vue.esm-bundler.DqIKZLqK.js";import{_ as l}from"./_plugin-vue_export-helper.BN1snXvA.js";import{C as x}from"./Blur.BIgRC1HX.js";import{C as b}from"./Index.uAkj14B_.js";import{L as C}from"./LicenseConditions.m3GuYfLc.js";const v={setup(){return{searchStatisticsStore:g()}},components:{Graph:$},props:{legendStyle:String},computed:{series(){var n,r,o,a;if(!((r=(n=this.searchStatisticsStore.data)==null?void 0:n.keywords)!=null&&r.distribution)||!((a=(o=this.searchStatisticsStore.data)==null?void 0:o.keywords)!=null&&a.distributionIntervals))return[];const e=this.searchStatisticsStore.data.keywords.distribution,s=this.searchStatisticsStore.data.keywords.distributionIntervals;return[{name:this.$t.__("Top 3 Position",this.$td),data:s.map(t=>({x:t.date,y:t.top3})),legend:{total:e.top3||"0"}},{name:this.$t.__("4-10 Position",this.$td),data:s.map(t=>({x:t.date,y:t.top10})),legend:{total:e.top10||"0"}},{name:this.$t.__("11-50 Position",this.$td),data:s.map(t=>({x:t.date,y:t.top50})),legend:{total:e.top50||"0"}},{name:this.$t.__("50-100 Position",this.$td),data:s.map(t=>({x:t.date,y:t.top100})),legend:{total:e.top100||"0"}}]}}},P={class:"aioseo-search-statistics-keywords-graph"};function G(e,s,n,r,o,a){const t=i("graph");return c(),h("div",P,[p(t,{series:a.series,loading:r.searchStatisticsStore.loading.keywords,"legend-style":n.legendStyle},null,8,["series","loading","legend-style"])])}const y=l(v,[["render",G]]),L={components:{CoreBlur:x,KeywordsGraph:y}};function T(e,s,n,r,o,a){const t=i("keywords-graph"),d=i("core-blur");return c(),_(d,null,{default:u(()=>[p(t,{"legend-style":"simple"})]),_:1})}const B=l(L,[["render",T]]),K={components:{Blur:B,Cta:b},data(){return{strings:{ctaHeader:this.$t.sprintf(this.$t.__("%1$sUpgrade your %2$s %3$s%4$s plan to see Keyword Positions",this.$td),`<a href="${this.$links.getPricingUrl("search-statistics","search-statistics-upsell")}" target="_blank">`,"AIOSEO","Pro","</a>"),ctaDescription:this.$t.__("Track how well keywords are ranking in search results over time based on their position and average CTR. This can help you understand the performance of keywords and identify any trends or fluctuations.",this.$td)}}}},U={class:"aioseo-search-statistics-keyword-rankings"},A=["innerHTML"];function D(e,s,n,r,o,a){const t=i("blur"),d=i("cta");return c(),h("div",U,[p(t),p(d,{type:4},{"header-text":u(()=>[w("span",{innerHTML:o.strings.ctaHeader},null,8,A)]),description:u(()=>[S(k(o.strings.ctaDescription),1)]),_:1})])}const H=l(K,[["render",D]]),N={setup(){return{licenseStore:f()}},mixins:[C],props:{redirects:Object},components:{KeywordsGraph:y,Upgrade:H}};function V(e,s,n,r,o,a){const t=i("keywords-graph",!0),d=i("upgrade");return c(),h("div",null,[e.shouldShowMain("search-statistics","keyword-rankings")||r.licenseStore.isUnlicensed?(c(),_(t,{key:0,"legend-style":"simple"})):m("",!0),e.shouldShowUpgrade("search-statistics","keyword-rankings")?(c(),_(d,{key:1})):m("",!0)])}const z=l(N,[["render",V]]);export{z as K};
