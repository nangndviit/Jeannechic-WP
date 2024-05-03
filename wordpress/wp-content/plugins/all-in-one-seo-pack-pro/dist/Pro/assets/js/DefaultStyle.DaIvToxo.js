import{u as p,a as $}from"./links.D18SrdNe.js";import{D as l}from"./Map.BgiN5dgS.js";import{C as y}from"./ImageUploader.DAeyT-Ge.js";import{C as u}from"./SettingsRow.BjeXKLX-.js";import{x as s,o as m,l as c,m as _,C as d}from"./vue.esm-bundler.DqIKZLqK.js";import{_ as f}from"./_plugin-vue_export-helper.BN1snXvA.js";const S={setup(){return{optionsStore:p(),postEditorStore:$()}},components:{CoreImageUploader:y,CoreSettingsRow:u},mixins:[l],data(){return{strings:{customMarker:this.$t.__("Custom Marker",this.$tdPro),minimumSize:this.$t.sprintf(this.$t.__("%1$sThe custom marker should be: 100x100 px.%2$s If the image exceeds those dimensions it could (partially) cover the info popup.",this.$tdPro),"<strong>","</strong>")}}},watch:{"getDataObject.customMarker"(t){if(this.$root._data.screenContext!=="metabox"){this.optionsStore.options.localBusiness.maps.customMarker=t;return}this.postEditorStore.currentPost.local_seo.maps.customMarker=t,this.postEditorStore.savePostState()}}};function M(t,e,h,g,o,i){const r=s("core-image-uploader"),a=s("core-settings-row");return m(),c(a,{name:o.strings.customMarker,align:""},{content:_(()=>[d(r,{"img-preview-max-width":"100px","img-preview-max-height":"100px",description:o.strings.minimumSize,modelValue:t.getDataObject.customMarker,"onUpdate:modelValue":e[0]||(e[0]=n=>t.getDataObject.customMarker=n)},null,8,["description","modelValue"])]),_:1},8,["name"])}const D=f(S,[["render",M]]),x={setup(){return{optionsStore:p()}},components:{CoreSettingsRow:u},mixins:[l],data(){return{strings:{defaultMapStyle:this.$t.__("Default Map Style",this.$tdPro)},defaultMapStyles:[{label:this.$t.__("Roadmap",this.$tdPro),value:"roadmap"},{label:this.$t.__("Hybrid",this.$tdPro),value:"hybrid"},{label:this.$t.__("Satellite",this.$tdPro),value:"satellite"},{label:this.$t.__("Terrain",this.$tdPro),value:"terrain"}]}},methods:{getValue(){return this.getDataObject.mapOptions.mapTypeId?this.defaultMapStyles.find(t=>t.value===this.getDataObject.mapOptions.mapTypeId):this.defaultMapStyles.find(t=>t.value===this.optionsStore.options.localBusiness.maps.mapOptions.mapTypeId)}}};function b(t,e,h,g,o,i){const r=s("base-select"),a=s("core-settings-row");return m(),c(a,{name:o.strings.defaultMapStyle,align:""},{content:_(()=>[d(r,{modelValue:i.getValue(),"onUpdate:modelValue":e[0]||(e[0]=n=>t.getDataObject.mapOptions.mapTypeId=n.value),options:o.defaultMapStyles},null,8,["modelValue","options"])]),_:1},8,["name"])}const V=f(x,[["render",b]]);export{D as L,V as a};