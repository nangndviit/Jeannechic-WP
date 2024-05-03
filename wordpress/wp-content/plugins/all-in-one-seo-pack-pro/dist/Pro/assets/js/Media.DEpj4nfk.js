import{d as N,u as B,f as F,c as M,e as H}from"./links.D18SrdNe.js";import{A as D}from"./AddonConditions.BlFrcZ9R.js";import{A as z,T as W}from"./TitleDescription.DbbYtFcB.js";import{B as U}from"./RadioToggle.olJdJ6Wy.js";import{C as q}from"./Card.ltM1bBnP.js";import{C as j}from"./Tabs.jOaaGWf1.js";import{C as Y}from"./ProBadge.CjKJMApR.js";import{C as V}from"./SettingsRow.BjeXKLX-.js";import{B as O}from"./Checkbox.DUOJ_PLM.js";import{C as J}from"./Blur.BIgRC1HX.js";import{C as I}from"./HtmlTagsEditor.C6R_toxB.js";import{G,a as E}from"./Row.DKhn_IWV.js";import{x as d,o as r,l as h,m as s,a as l,C as n,d as _,D as f,t as c,c as g,F as A,K as x,v as R,$ as Q,H as X,T as Z}from"./vue.esm-bundler.DqIKZLqK.js";import{_ as L}from"./_plugin-vue_export-helper.BN1snXvA.js";import{C as tt,S as et}from"./Schema.D-R88o2E.js";import{B as st}from"./Textarea.RI0SQiLK.js";import{C as it}from"./ExcludePosts.DGGBqBEf.js";import{C as ot}from"./Tooltip.Gayc6MvE.js";import{R as at}from"./RequiredPlans.BQ_aHZ5c.js";import{C as nt}from"./Index.uAkj14B_.js";import{P as rt}from"./PostTypes.Cef6XkQ_.js";import"./default-i18n.Bd0Z306Z.js";import"./helpers.BOFCPzAH.js";import"./addons.DR5v859-.js";import"./upperFirst.DLef_sD0.js";import"./_stringToArray.DnK4tKcY.js";import"./toString.BNLSY1cq.js";import"./Caret.CE8P-qnG.js";import"./JsonValues.D25FTfEu.js";import"./MaxCounts.DHV7qSQX.js";import"./RobotsMeta.RLs2O5Jw.js";import"./Editor.DDwyDEN5.js";import"./index.BWln78-O.js";import"./Tags.C8dN262w.js";import"./postSlug.DgPEdjzX.js";import"./metabox.DtF8nuvV.js";import"./cleanForSlug.1AmsoVq6.js";import"./_baseTrim.BYZhh0MR.js";import"./deburr.CJsb_ehd.js";import"./get.CYs9ONpq.js";import"./GoogleSearchPreview.COiebX3i.js";import"./strings.6as-7VnG.js";import"./isString.CT51n-I9.js";import"./constants.DpuIWwJ9.js";import"./Slide.Qz9BWVTI.js";import"./TruSeoScore.TjofuHRQ.js";import"./Ellipse.CwJ4-j4Z.js";import"./Information.DFP4LhAC.js";import"./Checkmark.CUxYD2Fh.js";import"./UnfilteredHtml.D65MfVHK.js";import"./AddPlus.C8-zNuV_.js";import"./External.BpSjuEvK.js";import"./license.CZ_ULMsy.js";const lt={components:{BaseCheckbox:O,BaseRadioToggle:U,CoreBlur:J,CoreHtmlTagsEditor:I,CoreSettingsRow:V,GridColumn:G,GridRow:E},data(){return{stripPunctuationSettings:[{value:"dashes",label:this.$t.__("Dashes (-)",this.$td)},{value:"underscores",label:this.$t.__("Underscores (_)",this.$td)},{value:"numbers",label:this.$t.__("Numbers (0-9)",this.$td)},{value:"plus",label:this.$t.__("Plus (+)",this.$td)},{value:"apostrophe",label:this.$t.__("Apostrophe (')",this.$td)},{value:"pound",label:this.$t.__("Pound (#)",this.$td)},{value:"ampersand",label:this.$t.__("Ampersand (&)",this.$td)}],strings:{attributeFormat:this.$t.sprintf(this.$t.__("%1$s Format",this.$td),this.$t.__("Title",this.$td)),clickToAddTags:this.$t.sprintf(this.$t.__("Click on the tags below to insert variables into your %1$s attribute.",this.$td),this.$t.__("Title",this.$td).toLowerCase()),stripPunctuation:this.$t.__("Strip Punctuation",this.$td),punctuationCharactersToKeep:this.$t.__("Punctuation Characters to Keep:",this.$td),casing:this.$t.__("Casing",this.$td),casingDescription:this.$t.__("Choose which casing should be applied to the attribute.",this.$td),wordsToStrip:this.$t.__("Words to Strip",this.$td),autogenerate:this.$t.sprintf(this.$t.__("Autogenerate %1$s on Upload",this.$td),this.$t.__("Title",this.$td))},tags:{title:{context:"imageSeoTitle",default:["image_title","separator_sa","site_title"]},altTag:{context:"imageSeoAlt",default:["alt_tag","separator_sa","site_title"]},caption:{context:"imageSeoCaption",default:["attachment_caption","separator_sa","site_title"]},description:{context:"imageSeoDescription",default:["attachment_description","separator_sa","site_title"]}},casings:{lowerCase:{label:this.$t.__("Lower Case",this.$td),description:this.$t.__("All letters are converted to lower case (small) letters.",this.$td)},titleCase:{label:this.$t.__("Title Case",this.$td),description:this.$t.__("Major words are capitalized and minor words remain in their original casing.",this.$td)},sentenceCase:{label:this.$t.__("Sentence Case",this.$td),description:this.$t.__("The first word of each sentence starts with a capital.",this.$td)}}}},props:{activeTab:Object}},ct={class:"aioseo-sa-image-seo"},dt={class:"global-robots-settings aioseo-description"},ut={class:"aioseo-description"},mt=l("br",null,null,-1),ht={class:"casing-options"};function pt(e,u,i,o,t,m){const b=d("base-radio-toggle"),p=d("core-settings-row"),$=d("core-html-tags-editor"),v=d("base-checkbox"),y=d("grid-column"),P=d("grid-row"),w=d("core-blur");return r(),h(w,null,{default:s(()=>[l("div",ct,[["caption","description"].includes("title")?(r(),h(p,{key:0,name:t.strings.autogenerate,align:""},{content:s(()=>[n(b,{name:"autogenerate",options:[{label:e.$constants.GLOBAL_STRINGS.disabled,value:!1,activeClass:"dark"},{label:e.$constants.GLOBAL_STRINGS.enabled,value:!0}]},null,8,["options"])]),_:1},8,["name"])):_("",!0),n(p,{name:t.strings.attributeFormat},{content:s(()=>[n($,{"line-numbers":!1,single:"","tags-context":t.tags.title.context,"default-tags":t.tags.title.default},{"tags-description":s(()=>[f(c(t.strings.clickToAddTags),1)]),_:1},8,["tags-context","default-tags"])]),_:1},8,["name"]),n(p,{name:t.strings.stripPunctuation,align:""},{content:s(()=>[n(b,{name:"stripPunctuation",options:[{label:e.$constants.GLOBAL_STRINGS.disabled,value:!1,activeClass:"dark"},{label:e.$constants.GLOBAL_STRINGS.enabled,value:!0}]},null,8,["options"]),l("div",dt,[l("strong",null,c(t.strings.punctuationCharactersToKeep),1),n(P,{class:"settings"},{default:s(()=>[(r(!0),g(A,null,x(t.stripPunctuationSettings,(S,T)=>(r(),h(y,{key:T,xl:"3",md:"4",sm:"6"},{default:s(()=>[n(v,{size:"medium"},{default:s(()=>[f(c(S.label),1)]),_:2},1024)]),_:2},1024))),128))]),_:1})])]),_:1},8,["name"]),n(p,{name:t.strings.casing,align:""},{content:s(()=>[n(b,{name:"casing",options:[{label:e.$constants.GLOBAL_STRINGS.disabled,value:"",activeClass:"dark"},{label:t.casings.lowerCase.label,value:"lower"},{label:t.casings.titleCase.label,value:"title"},{label:t.casings.sentenceCase.label,value:"sentence"}]},null,8,["options"]),l("div",ut,[l("span",null,c(t.strings.casingDescription),1),mt,l("ul",ht,[(r(!0),g(A,null,x(t.casings,(S,T)=>(r(),g("li",{key:T},[l("span",null,c(S.label),1),l("span",null,c(S.description),1)]))),128))])])]),_:1},8,["name"])])]),_:1})}const K=L(lt,[["render",pt]]),_t={setup(){return{tagsStore:N()}},mixins:[D],components:{Blur:K},data(){return{addonSlug:"aioseo-image-seo",strings:{imageSeoHeader:this.$t.__("Enable Image SEO on Your Site",this.$tdPro),ctaDescription:this.$t.__("The Image SEO module is a premium feature that enables you to globally control the title, alt tag, caption, description and filename of the images on your site.",this.$tdPro),learnMoreText:this.$t.__("Learn more about Image SEO",this.$tdPro),features:[this.$t.__("Autogenerate image attributes",this.$tdPro),this.$t.__("Clean uploaded image filenames",this.$tdPro),this.$t.__("Strip punctuation from image attributes",this.$tdPro),this.$t.__("Convert casing of image attributes",this.$tdPro)]}}},computed:{ctaButtonText(){return this.shouldShowUpdate?this.$t.__("Update Image SEO",this.$tdPro):this.$t.__("Activate Image SEO",this.$tdPro)}}},gt={class:"aioseo-sa-image-seo"};function bt(e,u,i,o,t,m){const b=d("blur");return r(),g("div",gt,[n(b),(r(),h(R(e.ctaComponent),{"addon-slug":t.addonSlug,"cta-header":t.strings.imageSeoHeader,"cta-description":t.strings.ctaDescription,"cta-button-text":m.ctaButtonText,"learn-more-text":t.strings.learnMoreText,"learn-more-link":e.$links.getDocUrl("imageSeo"),"feature-list":t.strings.features,"post-activation-promises":[o.tagsStore.getTags]},null,8,["addon-slug","cta-header","cta-description","cta-button-text","learn-more-text","learn-more-link","feature-list","post-activation-promises"]))])}const Tt=L(_t,[["render",bt]]),ft={setup(){return{optionsStore:B()}},components:{BaseCheckbox:O,BaseRadioToggle:U,BaseTextarea:st,CoreExcludePosts:it,CoreHtmlTagsEditor:I,CoreSettingsRow:V,CoreTooltip:ot,GridColumn:G,GridRow:E},data(){return{strings:{attributeFormat:this.$t.sprintf(this.$t.__("%1$s Format",this.$tdPro),this.activeTab.name),clickToAddTags:this.$t.sprintf(this.$t.__("Click on the tags below to insert variables into your %1$s attribute.",this.$tdPro),this.activeTab.name.toLowerCase()),stripPunctuation:this.$t.__("Strip Punctuation",this.$tdPro),charactersToKeep:this.$t.__("Characters to Exclude from Being Stripped:",this.$tdPro),charactersToConvert:this.$t.__("Characters to Convert to Spaces:",this.$tdPro),casing:this.$t.__("Casing",this.$tdPro),casingDescription:this.$t.__("Choose which casing should be applied to the attribute.",this.$tdPro),wordsToStrip:this.$t.__("Words to Strip",this.$tdPro),wordsToStripDescription:this.$t.__("Here you can add words that should be stripped from the filename, separated by a new line.",this.$tdPro),excludePostsPages:this.$t.__("Exclude Posts / Pages",this.$tdPro),excludeTerms:this.$t.__("Exclude Terms",this.$tdPro),excludeTermsDescription:this.$t.__("Any posts that are assigned to these terms will also be excluded.",this.$tdPro),autogenerate:this.$t.sprintf(this.$t.__("Autogenerate %1$s on Upload",this.$tdPro),this.activeTab.name),autogenerateDescriptions:{caption:this.$t.sprintf(this.$t.__("Choose whether %1$s should automatically generate a %2$s when new images are uploaded. If you disable this setting, you can still generate %3$s in the Media Library via our bulk action.",this.$tdPro),"AIOSEO",this.$t.__("caption",this.$tdPro),this.$t.__("captions",this.$tdPro)),description:this.$t.sprintf(this.$t.__("Choose whether %1$s should automatically generate a %2$s when new images are uploaded. If you disable this setting, you can still generate %3$s in the Media Library via our bulk action.",this.$tdPro),"AIOSEO",this.$t.__("description",this.$tdPro),this.$t.__("descriptions",this.$tdPro))},attributeDescriptions:{title:{first:this.$t.__("The title attribute is used to provide additional information about an image and can be viewed when you hover over the image.",this.$tdPro),second:this.$t.sprintf(this.$t.__("Below you can control how your %1$s look like by setting a format (similar to the SEO title/description formats), stripping punctuation and converting the casing.",this.$tdPro),this.$t.__("title attributes",this.$tdPro))},altTag:{first:this.$t.__("The alt tag attribute is used to display text that describes the image if it cannot be rendered by the browser. Its primary goal is to make images more accessible to visually impaired users, but it also used as a ranking factor by search engines.",this.$tdPro),second:this.$t.sprintf(this.$t.__("Below you can control how your %1$s look like by setting a format (similar to the SEO title/description formats), stripping punctuation and converting the casing.",this.$tdPro),this.$t.__("alt tag attributes",this.$tdPro))},caption:{first:this.$t.__("The caption is usually a few lines of text that are displayed underneath an image to provide more context or explain what can be seen in the picture.",this.$tdPro),second:this.$t.sprintf(this.$t.__("Below you can control how your %1$s look like by setting a format (similar to the SEO title/description formats), stripping punctuation and converting the casing.",this.$tdPro),this.$t.__("image captions",this.$tdPro))},description:{first:this.$t.__("The description is the text that is displayed on an image's attachment page",this.$tdPro),second:this.$t.sprintf(this.$t.__("Below you can control how your %1$s look like by setting a format (similar to the SEO title/description formats), stripping punctuation and converting the casing.",this.$tdPro),this.$t.__("attachment page descriptions",this.$tdPro))},filename:{first:this.$t.__("The filename is name of the image file when it is uploaded to the Media Library. The more descriptive and relevant the filename is, the more likely search engines will include in their results.",this.$tdPro),second:this.$t.sprintf(this.$t.__("Below you can control how your %1$s look like by stripping punctuation, specific words and converting the casing.",this.$tdPro),this.$t.__("filenames",this.$tdPro))}}},charactersToKeep:[{value:"dashes",label:this.$t.__("Dashes (-)",this.$tdPro)},{value:"underscores",label:this.$t.__("Underscores (_)",this.$tdPro)},{value:"numbers",label:this.$t.__("Numbers (0-9)",this.$tdPro)},{value:"plus",label:this.$t.__("Plus (+)",this.$tdPro)},{value:"apostrophe",label:this.$t.__("Apostrophe (')",this.$tdPro)},{value:"pound",label:this.$t.__("Pound (#)",this.$tdPro)},{value:"ampersand",label:this.$t.__("Ampersand (&)",this.$tdPro)}],charactersToConvert:[{value:"dashes",label:this.$t.__("Dashes (-)",this.$tdPro)},{value:"underscores",label:this.$t.__("Underscores (_)",this.$tdPro)}],casings:{lowerCase:{label:this.$t.__("Lower Case",this.$tdPro),description:this.$t.__("All letters are converted to lower case (small) letters.",this.$tdPro)},titleCase:{label:this.$t.__("Title Case",this.$tdPro),description:this.$t.__("Major words are capitalized and minor words remain in their original casing.",this.$tdPro)},sentenceCase:{label:this.$t.__("Sentence Case",this.$tdPro),description:this.$t.__("The first word of each sentence starts with a capital.",this.$tdPro)}},tags:{title:{context:"imageSeoTitle",default:["image_title","separator_sa","site_title"]},altTag:{context:"imageSeoAlt",default:["alt_tag","separator_sa","site_title"]},caption:{context:"imageSeoCaption",default:["attachment_caption","separator_sa","site_title"]},description:{context:"imageSeoDescription",default:["attachment_description","separator_sa","site_title"]}}}},computed:{isFilenameTab(){return this.activeTab.slug==="filename"},filteredCharactersToKeep(){const e=["plus","apostrophe","pound","ampersand"];return this.charactersToKeep.filter(i=>this.activeTab.slug!=="filename"?!0:!e.includes(i.value)).map(i=>{var o;return(o=this.optionsStore.options.image[this.activeTab.slug].charactersToConvert)!=null&&o[i.value]?i.disabled=!0:i.disabled=!1,i})},filteredCharactersToConvert(){return this.charactersToConvert.map(e=>(this.optionsStore.options.image[this.activeTab.slug].charactersToKeep[e.value]?e.disabled=!0:e.disabled=!1,e))}},methods:{charactersToKeepTooltipText(e){return this.$t.sprintf(this.$t.__("Excluding %1$s is disabled when converting to spaces is enabled.",this.$tdPro),e.toLowerCase())},charactersToConvertTooltipText(e){return this.$t.sprintf(this.$t.__("Converting %1$s to spaces is disabled when excluding from stripping.",this.$tdPro),e.toLowerCase())}},props:{activeTab:Object}},vt={class:"aioseo-sa-image-seo"},$t={class:"aioseo-settings-row aioseo-section-description"},St=["innerHTML"],Ct={class:"aioseo-description"},yt={key:0,class:"global-robots-settings aioseo-description"},Pt={key:0},wt={class:"aioseo-description"},kt=l("br",null,null,-1),At={class:"casing-options"},xt={class:"aioseo-description"},Lt={key:3,slug:"advancedSettings"},Ut={class:"aioseo-description"};function Vt(e,u,i,o,t,m){const b=d("base-radio-toggle"),p=d("core-settings-row"),$=d("core-html-tags-editor"),v=d("base-checkbox"),y=d("core-tooltip"),P=d("grid-column"),w=d("grid-row"),S=d("base-textarea"),T=d("core-exclude-posts");return r(),g("div",vt,[l("div",$t,[l("div",null,c(t.strings.attributeDescriptions[i.activeTab.slug].first),1),l("div",null,[f(c(t.strings.attributeDescriptions[i.activeTab.slug].second)+" ",1),l("span",{innerHTML:e.$links.getDocLink(e.$constants.GLOBAL_STRINGS.learnMore,"imageSeo",!0)},null,8,St)])]),["caption","description"].includes(i.activeTab.slug)?(r(),h(p,{key:0,name:t.strings.autogenerate,align:""},{content:s(()=>[n(b,{modelValue:o.optionsStore.options.image[i.activeTab.slug].autogenerate,"onUpdate:modelValue":u[0]||(u[0]=a=>o.optionsStore.options.image[i.activeTab.slug].autogenerate=a),name:"autogenerate",options:[{label:e.$constants.GLOBAL_STRINGS.disabled,value:!1,activeClass:"dark"},{label:e.$constants.GLOBAL_STRINGS.enabled,value:!0}]},null,8,["modelValue","options"]),l("div",Ct,c(t.strings.autogenerateDescriptions[i.activeTab.slug]),1)]),_:1},8,["name"])):_("",!0),m.isFilenameTab?_("",!0):(r(),h(p,{key:1,name:t.strings.attributeFormat},{content:s(()=>[n($,{modelValue:o.optionsStore.options.image[i.activeTab.slug].format,"onUpdate:modelValue":u[1]||(u[1]=a=>o.optionsStore.options.image[i.activeTab.slug].format=a),"line-numbers":!1,single:"","tags-context":t.tags[i.activeTab.slug].context,"default-tags":t.tags[i.activeTab.slug].default,"disable-emoji":""},{"tags-description":s(()=>[f(c(t.strings.clickToAddTags),1)]),_:1},8,["modelValue","tags-context","default-tags"])]),_:1},8,["name"])),n(p,{name:t.strings.stripPunctuation,align:""},{content:s(()=>[n(b,{modelValue:o.optionsStore.options.image[i.activeTab.slug].stripPunctuation,"onUpdate:modelValue":u[2]||(u[2]=a=>o.optionsStore.options.image[i.activeTab.slug].stripPunctuation=a),name:"stripPunctuation",options:[{label:e.$constants.GLOBAL_STRINGS.disabled,value:!1,activeClass:"dark"},{label:e.$constants.GLOBAL_STRINGS.enabled,value:!0}]},null,8,["modelValue","options"]),o.optionsStore.options.image[i.activeTab.slug].stripPunctuation?(r(),g("div",yt,[l("div",null,[l("strong",null,c(t.strings.charactersToKeep),1),n(w,{class:"characters-to-keep"},{default:s(()=>[(r(!0),g(A,null,x(m.filteredCharactersToKeep,(a,k)=>(r(),h(P,{class:"characters-grid",key:k,xl:"3",md:"4",sm:"6"},{default:s(()=>[a.disabled?(r(),h(y,{key:0},{tooltip:s(()=>[f(c(m.charactersToKeepTooltipText(a.value)),1)]),default:s(()=>[n(v,{size:"medium",modelValue:o.optionsStore.options.image[i.activeTab.slug].charactersToKeep[a.value],"onUpdate:modelValue":C=>o.optionsStore.options.image[i.activeTab.slug].charactersToKeep[a.value]=C,disabled:a.disabled},{default:s(()=>[f(c(a.label),1)]),_:2},1032,["modelValue","onUpdate:modelValue","disabled"])]),_:2},1024)):_("",!0),a.disabled?_("",!0):(r(),h(v,{key:1,size:"medium",modelValue:o.optionsStore.options.image[i.activeTab.slug].charactersToKeep[a.value],"onUpdate:modelValue":C=>o.optionsStore.options.image[i.activeTab.slug].charactersToKeep[a.value]=C,disabled:a.disabled},{default:s(()=>[f(c(a.label),1)]),_:2},1032,["modelValue","onUpdate:modelValue","disabled"]))]),_:2},1024))),128))]),_:1})]),!m.isFilenameTab&&m.filteredCharactersToConvert.length?(r(),g("div",Pt,[l("strong",null,c(t.strings.charactersToConvert),1),n(w,{class:"characters-to-convert"},{default:s(()=>[(r(!0),g(A,null,x(m.filteredCharactersToConvert,(a,k)=>(r(),h(P,{class:"characters-grid",key:k,xl:"3",md:"4",sm:"6"},{default:s(()=>[a.disabled?(r(),h(y,{key:0},{tooltip:s(()=>[f(c(m.charactersToConvertTooltipText(a.value)),1)]),default:s(()=>[n(v,{size:"medium",modelValue:o.optionsStore.options.image[i.activeTab.slug].charactersToConvert[a.value],"onUpdate:modelValue":C=>o.optionsStore.options.image[i.activeTab.slug].charactersToConvert[a.value]=C,disabled:a.disabled},{default:s(()=>[f(c(a.label),1)]),_:2},1032,["modelValue","onUpdate:modelValue","disabled"])]),_:2},1024)):_("",!0),a.disabled?_("",!0):(r(),h(v,{key:1,size:"medium",modelValue:o.optionsStore.options.image[i.activeTab.slug].charactersToConvert[a.value],"onUpdate:modelValue":C=>o.optionsStore.options.image[i.activeTab.slug].charactersToConvert[a.value]=C,disabled:a.disabled},{default:s(()=>[f(c(a.label),1)]),_:2},1032,["modelValue","onUpdate:modelValue","disabled"]))]),_:2},1024))),128))]),_:1})])):_("",!0)])):_("",!0)]),_:1},8,["name"]),n(p,{name:t.strings.casing,align:""},{content:s(()=>[n(b,{modelValue:o.optionsStore.options.image[i.activeTab.slug].casing,"onUpdate:modelValue":u[3]||(u[3]=a=>o.optionsStore.options.image[i.activeTab.slug].casing=a),name:"casing",options:[{label:e.$constants.GLOBAL_STRINGS.disabled,value:"",activeClass:"dark"},{label:t.casings.lowerCase.label,value:"lower"},{label:t.casings.titleCase.label,value:"title"},{label:t.casings.sentenceCase.label,value:"sentence"}]},null,8,["modelValue","options"]),l("div",wt,[l("span",null,c(t.strings.casingDescription),1),kt,l("ul",At,[(r(!0),g(A,null,x(t.casings,(a,k)=>(r(),g("li",{key:k},[l("span",null,c(a.label),1),l("span",null,c(a.description),1)]))),128))])])]),_:1},8,["name"]),m.isFilenameTab?(r(),h(p,{key:2,name:t.strings.wordsToStrip,align:""},{content:s(()=>[n(S,{minHeight:200,modelValue:o.optionsStore.options.image[i.activeTab.slug].wordsToStrip,"onUpdate:modelValue":u[4]||(u[4]=a=>o.optionsStore.options.image[i.activeTab.slug].wordsToStrip=a)},null,8,["modelValue"]),l("div",xt,c(t.strings.wordsToStripDescription),1)]),_:1},8,["name"])):_("",!0),["title","altTag"].includes(i.activeTab.slug)?(r(),g("div",Lt,[n(p,{name:t.strings.excludePostsPages,class:"aioseo-exclude-pages-posts",align:""},{content:s(()=>[n(T,{options:o.optionsStore.options.image[i.activeTab.slug].advancedSettings,type:"posts"},null,8,["options"])]),_:1},8,["name"]),n(p,{name:t.strings.excludeTerms,class:"aioseo-exclude-terms",align:""},{content:s(()=>[n(T,{options:o.optionsStore.options.image[i.activeTab.slug].advancedSettings,type:"terms"},null,8,["options"]),l("div",Ut,c(t.strings.excludeTermsDescription),1)]),_:1},8,["name"])])):_("",!0)])}const Bt=L(ft,[["render",Vt]]),Dt={setup(){return{licenseStore:F()}},components:{Blur:K,RequiredPlans:at,Cta:nt},data(){return{strings:{titleAttributeFormat:this.$t.__("Title Attribute Format",this.$td),ctaDescription:this.$t.__("The Image SEO module is a premium feature that enables you to globally control the title, alt tag, caption, description and filename of the images on your site.",this.$td),ctaButtonText:this.$t.__("Unlock Image SEO",this.$td),ctaHeader:this.$t.sprintf(this.$t.__("Image SEO is a %1$s Feature",this.$td),"PRO")},features:[this.$t.__("Autogenerate image attributes",this.$td),this.$t.__("Clean uploaded image filenames",this.$td),this.$t.__("Strip punctuation from image attributes",this.$td),this.$t.__("Convert casing of image attributes",this.$td)]}}},Ot={class:"aioseo-sa-image-seo"};function It(e,u,i,o,t,m){const b=d("blur"),p=d("required-plans"),$=d("cta");return r(),g("div",Ot,[n(b),n($,{"cta-link":e.$links.getPricingUrl("image-seo","image-seo-upsell"),"button-text":t.strings.ctaButtonText,"learn-more-link":e.$links.getUpsellUrl("image-seo",null,e.$isPro?"pricing":"liteUpgrade"),"feature-list":t.features,"hide-bonus":!o.licenseStore.isUnlicensed},{"header-text":s(()=>[f(c(t.strings.ctaHeader),1)]),description:s(()=>[n(p,{addon:"aioseo-image-seo"}),f(" "+c(t.strings.ctaDescription),1)]),_:1},8,["cta-link","button-text","learn-more-link","feature-list","hide-bonus"])])}const Gt=L(Dt,[["render",It],["__scopeId","data-v-7fc5d2be"]]),Et={setup(){return{optionsStore:B(),rootStore:M(),settingsStore:H()}},mixins:[D,rt],components:{Advanced:z,BaseRadioToggle:U,CoreCard:q,CoreMainTabs:j,CoreProBadge:Y,CoreSettingsRow:V,Cta:Tt,CustomFields:tt,ImageSeo:Bt,Lite:Gt,Schema:et,TitleDescription:W},data(){return{imageSeoKey:0,addonSlug:"aioseo-image-seo",internalDebounce:!1,imageSeoActiveTab:{slug:"title",name:this.$t.__("Title",this.$td),pro:!0},strings:{redirectAttachmentUrls:this.$t.__("Redirect Attachment URLs",this.$td),attachment:this.$t.__("Attachment",this.$td),attachmentParent:this.$t.__("Attachment Parent",this.$td),attachmentUrlsDescription:this.$t.__("We recommended redirecting attachment URLs back to the attachment since the default WordPress attachment pages have little SEO value.",this.$td),imageSeo:this.$t.__("Image SEO",this.$td),advancedSettings:this.$t.__("Advanced Settings",this.$td)},tabs:{attachments:[{slug:"title-description",name:this.$t.__("Title & Description",this.$td),access:"aioseo_search_appearance_settings",pro:!1},{slug:"Schema",name:this.$t.__("Schema Markup",this.$td),access:"aioseo_search_appearance_settings",pro:!0},{slug:"advanced",name:this.$t.__("Advanced",this.$td),access:"aioseo_search_appearance_settings",pro:!1}],imageSeo:[{slug:"title",name:this.$t.__("Title",this.$td),pro:!0},{slug:"altTag",name:this.$t.__("Alt Tag",this.$td),pro:!0},{slug:"caption",name:this.$t.__("Caption",this.$td),pro:!0},{slug:"description",name:this.$t.__("Description",this.$td),pro:!0},{slug:"filename",name:this.$t.__("Filename",this.$td),pro:!0}]}}},computed:{postType(){return this.rootStore.aioseo.postData.postTypes.filter(e=>e.name==="attachment")[0]}},methods:{processChangeTab(e,u){this.internalDebounce||(this.internalDebounce=!0,this.settingsStore.changeTab({slug:`${e}SA`,value:u}),setTimeout(()=>{this.internalDebounce=!1},50))},setImageSeoActiveTab(e){this.imageSeoActiveTab=this.tabs.imageSeo.find(u=>u.slug===e),this.imageSeoKey++}}},Rt={class:"aioseo-search-appearance-content-types"},Kt={class:"aioseo-description"};function Nt(e,u,i,o,t,m){const b=d("base-radio-toggle"),p=d("core-settings-row"),$=d("core-main-tabs"),v=d("core-card"),y=d("core-pro-badge"),P=d("image-seo"),w=d("cta"),S=d("lite");return r(),g("div",Rt,[n(v,{slug:`${m.postType.name}SA`},Q({header:s(()=>[l("div",{class:X(["icon dashicons",e.getPostIconClass(m.postType.icon)])},null,2),l("div",null,c(m.postType.label),1)]),"before-tabs":s(()=>[n(p,{name:t.strings.redirectAttachmentUrls,align:""},{content:s(()=>[n(b,{modelValue:o.optionsStore.dynamicOptions.searchAppearance.postTypes.attachment.redirectAttachmentUrls,"onUpdate:modelValue":u[0]||(u[0]=T=>o.optionsStore.dynamicOptions.searchAppearance.postTypes.attachment.redirectAttachmentUrls=T),name:"redirectAttachmentUrls",options:[{label:e.$constants.GLOBAL_STRINGS.disabled,value:"disabled",activeClass:"dark"},{label:t.strings.attachment,value:"attachment"},{label:t.strings.attachmentParent,value:"attachment_parent"}]},null,8,["modelValue","options"]),l("div",Kt,c(t.strings.attachmentUrlsDescription),1)]),_:1},8,["name"])]),default:s(()=>[o.optionsStore.dynamicOptions.searchAppearance.postTypes.attachment.redirectAttachmentUrls==="disabled"?(r(),h(Z,{key:0,name:"route-fade",mode:"out-in"},{default:s(()=>[(r(),h(R(o.settingsStore.settings.internalTabs[`${m.postType.name}SA`]),{object:m.postType,separator:o.optionsStore.options.searchAppearance.global.separator,options:o.optionsStore.dynamicOptions.searchAppearance.postTypes[m.postType.name],type:"postTypes"},null,8,["object","separator","options"]))]),_:1})):_("",!0)]),_:2},[o.optionsStore.dynamicOptions.searchAppearance.postTypes.attachment.redirectAttachmentUrls==="disabled"?{name:"tabs",fn:s(()=>[n($,{tabs:t.tabs.attachments,showSaveButton:!1,active:o.settingsStore.settings.internalTabs[`${m.postType.name}SA`],internal:"",onChanged:u[1]||(u[1]=T=>m.processChangeTab(m.postType.name,T))},null,8,["tabs","active"])]),key:"0"}:void 0]),1032,["slug"]),n(v,{slug:"imageSeo",noSlide:!e.shouldShowMain},{header:s(()=>[l("span",null,c(t.strings.imageSeo),1),n(y)]),tabs:s(()=>[n($,{tabs:t.tabs.imageSeo,showSaveButton:!1,active:t.imageSeoActiveTab.slug,internal:"",onChanged:u[2]||(u[2]=T=>m.setImageSeoActiveTab(T))},null,8,["tabs","active"])]),default:s(()=>[e.shouldShowMain?(r(),h(P,{activeTab:t.imageSeoActiveTab,key:t.imageSeoKey},null,8,["activeTab"])):_("",!0),e.shouldShowUpdate||e.shouldShowActivate?(r(),h(w,{key:1})):_("",!0),e.shouldShowLite?(r(),h(S,{key:2})):_("",!0)]),_:1},8,["noSlide"])])}const Ne=L(Et,[["render",Nt]]);export{Ne as default};
