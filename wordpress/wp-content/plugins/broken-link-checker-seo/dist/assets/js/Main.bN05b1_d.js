import{aB as n,v as s,y as a,o as i}from"./index.MAPGMl_Z.js";import p from"./Connect.DtQXVB4H.js";import c from"./Success.CGe2EMYM.js";import u from"./Welcome.DgMCXHsd.js";import{_ as m}from"./dynamic-import-helper._aqDH9xp.js";import"./SetupWizard.BRj8cUen.js";import"./Index.B44_a3kG.js";import"./LicenseUpgrade.B79nrkvy.js";import"./datetime.lrMItkpN.js";import"./Close.ywjiNtDt.js";import"./Button.CyqkHmlf.js";import"./Header.CE4-R789.js";import"./Logo.CeYg6D5b.js";const l={setup(){return{setupWizardStore:n()}},components:{Connect:p,Success:c,Welcome:u},methods:{deleteStage(o){const t=[...this.setupWizardStore.stages],r=t.findIndex(e=>o===e);r!==-1&&t.splice(r,1),this.setupWizardStore.stages=t}},mounted(){this.setupWizardStore.currentStage=this.stage}};function f(o,t,r,e,_,d){return i(),s(a(o.$route.name))}const L=m(l,[["render",f]]);export{L as default};