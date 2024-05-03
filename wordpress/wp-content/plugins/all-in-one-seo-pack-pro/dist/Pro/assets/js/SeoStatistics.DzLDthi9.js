import{G as C,f as B}from"./links.D18SrdNe.js";import{C as U}from"./Blur.BIgRC1HX.js";import{C as P}from"./Card.ltM1bBnP.js";import{G as L,S as F}from"./SeoStatisticsOverview.E9u2we1n.js";import{G as M,a as G}from"./Row.DKhn_IWV.js";import{P as O}from"./PostsTable.C6e3V3Q1.js";import{x as e,o as u,l as w,m as r,a as v,C as i,D as f,t as g,c as T,d as k}from"./vue.esm-bundler.DqIKZLqK.js";import{_ as S}from"./_plugin-vue_export-helper.BN1snXvA.js";import{C as q}from"./Index.uAkj14B_.js";import{R as D}from"./RequiredPlans.BQ_aHZ5c.js";import{P as E}from"./PostTypes.Cef6XkQ_.js";import{L as H}from"./LicenseConditions.m3GuYfLc.js";import"./default-i18n.Bd0Z306Z.js";import"./helpers.BOFCPzAH.js";import"./Tooltip.Gayc6MvE.js";import"./Caret.CE8P-qnG.js";import"./index.BWln78-O.js";import"./Slide.Qz9BWVTI.js";import"./numbers.zAmItkHM.js";import"./Index.Dd1ElObi.js";import"./DeleteWarning.B2t1LanR.js";import"./UserAvatar.D_X43R2n.js";import"./Profile.DGiNT8pI.js";import"./Eye.BRrFCfMQ.js";import"./license.CZ_ULMsy.js";import"./upperFirst.DLef_sD0.js";import"./_stringToArray.DnK4tKcY.js";import"./toString.BNLSY1cq.js";import"./WpTable.yjBoG_Qq.js";import"./ScoreButton.BYXILR6h.js";import"./Table.DPPHow5O.js";import"./IndexStatus.UMK1d_xE.js";import"./Calendar.q2eLUa1q.js";import"./Mobile.Bg07sdbv.js";import"./Checkmark.CUxYD2Fh.js";import"./Link.DbKgNI4p.js";import"./CheckSolid.BcqC1JWM.js";import"./CloseSolid.CKI9sS42.js";import"./addons.DR5v859-.js";import"./Affiliate.CZtLUjwg.js";import"./Suggestion.DZXjrRRX.js";import"./constants.DpuIWwJ9.js";import"./_arrayEach.Fgt6pfHj.js";import"./_getTag.B9PhEBdR.js";import"./_getAllKeysIn.BmgwIvpt.js";const N={setup(){return{searchStatisticsStore:C()}},components:{CoreBlur:U,CoreCard:P,Graph:L,GridColumn:M,GridRow:G,PostsTable:O,SeoStatisticsOverview:F},data(){return{strings:{seoStatisticsCard:this.$t.__("SEO Statistics",this.$td),seoStatisticsTooltip:this.$t.__("The following SEO Statistics graphs are useful metrics for understanding the visibility of your website or pages in search results and can help you identify trends or changes over time.<br /><br />Note: This data is capped at the top 100 keywords per day to speed up processing and to help you prioritize your SEO efforts, so while the data may seem inconsistent with Google Search Console, this is intentional.",this.$td),contentCard:this.$t.__("Content",this.$td),postsTooltip:this.$t.__("These lists can be useful for understanding the performance of specific pages or posts and identifying opportunities for improvement. For example, the top winning content may be good candidates for further optimization or promotion, while the top losing may need to be reevaluated and potentially updated.",this.$td)},defaultPages:{rows:[],totals:{page:0,pages:0,total:0}}}},computed:{series(){var s,a,n,o;return!((a=(s=this.searchStatisticsStore.data)==null?void 0:s.seoStatistics)!=null&&a.statistics)||!((o=(n=this.searchStatisticsStore.data)==null?void 0:n.seoStatistics)!=null&&o.intervals)?[]:[{name:this.$t.__("Search Impressions",this.$td),data:this.searchStatisticsStore.data.seoStatistics.intervals.map(t=>({x:t.date,y:t.impressions})),legend:{total:this.searchStatisticsStore.data.seoStatistics.statistics.impressions}},{name:this.$t.__("Search Clicks",this.$td),data:this.searchStatisticsStore.data.seoStatistics.intervals.map(t=>({x:t.date,y:t.clicks})),legend:{total:this.searchStatisticsStore.data.seoStatistics.statistics.clicks}}]}}},R={class:"aioseo-search-statistics-dashboard"},z=["innerHTML"];function I(s,a,n,o,t,m){const l=e("seo-statistics-overview"),p=e("graph"),d=e("core-card"),y=e("posts-table"),b=e("grid-column"),$=e("grid-row"),h=e("core-blur");return u(),w(h,null,{default:r(()=>[v("div",R,[i($,null,{default:r(()=>[i(b,null,{default:r(()=>[i(d,{class:"aioseo-seo-statistics-card",slug:"seoPerformance","header-text":t.strings.seoStatisticsCard,toggles:!1,"no-slide":""},{tooltip:r(()=>[v("span",{innerHTML:t.strings.seoStatisticsTooltip},null,8,z)]),default:r(()=>[i(l,{statistics:["impressions","clicks","ctr","position"],"show-graph":!1,view:"side-by-side"}),i(p,{"multi-axis":"",series:m.series,"legend-style":"simple"},null,8,["series"])]),_:1},8,["header-text"]),i(d,{slug:"posts","header-text":t.strings.contentCard,toggles:!1,"no-slide":""},{tooltip:r(()=>[f(g(t.strings.postsTooltip),1)]),default:r(()=>{var c,_,x;return[i(y,{posts:((x=(_=(c=o.searchStatisticsStore.data)==null?void 0:c.seoStatistics)==null?void 0:_.pages)==null?void 0:x.paginated)||t.defaultPages,columns:["postTitle","indexStatus","seoScore","clicksSortable","impressionsSortable","positionSortable","diffPositionSortable"],"show-items-per-page":"","show-table-footer":""},null,8,["posts"])]}),_:1},8,["header-text"])]),_:1})]),_:1})])]),_:1})}const V=S(N,[["render",I]]),A={setup(){return{licenseStore:B()}},components:{Blur:V,Cta:q,RequiredPlans:D},data(){return{strings:{ctaButtonText:this.$t.__("Unlock Search Statistics",this.$td),ctaHeader:this.$t.sprintf(this.$t.__("Search Statistics is a %1$s Feature",this.$td),"PRO"),ctaDescription:this.$t.__("Connect your site to Google Search Console to receive insights on how content is being discovered. Identify areas for improvement and drive traffic to your website.",this.$td),thisFeatureRequires:this.$t.__("This feature requires one of the following plans:",this.$td),feature1:this.$t.__("Search traffic insights",this.$td),feature2:this.$t.__("Track page rankings",this.$td),feature3:this.$t.__("Track keyword rankings",this.$td),feature4:this.$t.__("Speed tests for individual pages/posts",this.$td)}}}},W={class:"aioseo-search-statistics-seo-statistics"};function j(s,a,n,o,t,m){const l=e("blur"),p=e("required-plans"),d=e("cta");return u(),T("div",W,[i(l),i(d,{"cta-link":s.$links.getPricingUrl("search-statistics","search-statistics-upsell","seo-statistics"),"button-text":t.strings.ctaButtonText,"learn-more-link":s.$links.getUpsellUrl("search-statistics","seo-statistics",s.$isPro?"pricing":"liteUpgrade"),"feature-list":[t.strings.feature1,t.strings.feature2,t.strings.feature3,t.strings.feature4],"align-top":"","hide-bonus":!o.licenseStore.isUnlicensed},{"header-text":r(()=>[f(g(t.strings.ctaHeader),1)]),description:r(()=>[i(p,{"core-feature":["search-statistics","seo-statistics"]}),f(" "+g(t.strings.ctaDescription),1)]),_:1},8,["cta-link","button-text","learn-more-link","feature-list","hide-bonus"])])}const J=S(A,[["render",j]]),K={setup(){return{searchStatisticsStore:C()}},components:{CoreCard:P,Graph:L,GridColumn:M,GridRow:G,PostsTable:O,SeoStatisticsOverview:F},mixins:[E],data(){return{initialTableFilter:"",strings:{seoStatisticsCard:this.$t.__("SEO Statistics",this.$tdPro),seoStatisticsTooltip:this.$t.__("The following SEO Statistics graphs are useful metrics for understanding the visibility of your website or pages in search results and can help you identify trends or changes over time.<br /><br />Note: This data is capped at the top 100 keywords per day to speed up processing and to help you prioritize your SEO efforts, so while the data may seem inconsistent with Google Search Console, this is intentional.",this.$tdPro),contentCard:this.$t.__("Content Performance",this.$tdPro),postsTooltip:this.$t.__("These lists can be useful for understanding the performance of specific pages or posts and identifying opportunities for improvement. For example, the top winning content may be good candidates for further optimization or promotion, while the top losing may need to be reevaluated and potentially updated.",this.$tdPro)},defaultPages:{rows:[],totals:{page:0,pages:0,total:0}}}},computed:{series(){var s,a,n,o;return!((a=(s=this.searchStatisticsStore.data)==null?void 0:s.seoStatistics)!=null&&a.statistics)||!((o=(n=this.searchStatisticsStore.data)==null?void 0:n.seoStatistics)!=null&&o.intervals)?[]:[{name:this.$t.__("Search Impressions",this.$tdPro),data:this.searchStatisticsStore.data.seoStatistics.intervals.map(t=>({x:t.date,y:t.impressions})),legend:{total:this.searchStatisticsStore.data.seoStatistics.statistics.impressions}},{name:this.$t.__("Search Clicks",this.$tdPro),data:this.searchStatisticsStore.data.seoStatistics.intervals.map(t=>({x:t.date,y:t.clicks})),legend:{total:this.searchStatisticsStore.data.seoStatistics.statistics.clicks}}]}},beforeMount(){var s;if(Object.keys((s=this.$route)==null?void 0:s.query).includes("tab"))switch(this.$route.query.tab){case"TopLosingPages":this.initialTableFilter="topLosing";break;case"TopWinningPages":this.initialTableFilter="topWinning";break;default:this.initialTableFilter="all"}},mounted(){this.searchStatisticsStore.isConnected&&this.searchStatisticsStore.loadInitialData()}},Q={class:"aioseo-search-statistics-site-statistics"},X=["innerHTML"];function Y(s,a,n,o,t,m){const l=e("seo-statistics-overview"),p=e("graph"),d=e("core-card"),y=e("posts-table"),b=e("grid-column"),$=e("grid-row");return u(),T("div",Q,[i($,null,{default:r(()=>[i(b,null,{default:r(()=>[i(d,{class:"aioseo-seo-statistics-card",slug:"seoPerformance","header-text":t.strings.seoStatisticsCard,toggles:!1,"no-slide":""},{tooltip:r(()=>[v("span",{innerHTML:t.strings.seoStatisticsTooltip},null,8,X)]),default:r(()=>{var h,c;return[i(l,{statistics:["impressions","clicks","ctr","position"],"show-graph":!1,view:"side-by-side"}),i(p,{"multi-axis":"",series:m.series,loading:o.searchStatisticsStore.loading.seoStatistics,"legend-style":"simple",timelineMarkers:(c=(h=o.searchStatisticsStore.data)==null?void 0:h.seoStatistics)==null?void 0:c.timelineMarkers},null,8,["series","loading","timelineMarkers"])]}),_:1},8,["header-text"]),i(d,{slug:"posts","header-text":t.strings.contentCard,toggles:!1,"no-slide":""},{tooltip:r(()=>[f(g(t.strings.postsTooltip),1)]),default:r(()=>{var h,c,_;return[i(y,{posts:((_=(c=(h=o.searchStatisticsStore.data)==null?void 0:h.seoStatistics)==null?void 0:c.pages)==null?void 0:_.paginated)||t.defaultPages,columns:["postTitle","indexStatus","seoScore","clicksSortable","impressionsSortable","positionSortable"],"append-columns":{all:"diffPosition",topLosing:"diffDecay",topWinning:"diffDecay"},isLoading:o.searchStatisticsStore.loading.seoStatistics,initialFilter:t.initialTableFilter,"show-items-per-page":"","show-table-footer":""},null,8,["posts","isLoading","initialFilter"])]}),_:1},8,["header-text"])]),_:1})]),_:1})])}const Z=S(K,[["render",Y]]),tt={mixins:[H],components:{SeoStatistics:Z,Lite:J}},st={class:"aioseo-search-statistics-seo-statistics"};function et(s,a,n,o,t,m){const l=e("seo-statistics",!0),p=e("lite");return u(),T("div",st,[s.shouldShowMain("search-statistics","seo-statistics")?(u(),w(l,{key:0})):k("",!0),s.shouldShowUpgrade("search-statistics","seo-statistics")||s.shouldShowLite?(u(),w(p,{key:1})):k("",!0)])}const Jt=S(tt,[["render",et]]);export{Jt as default};
