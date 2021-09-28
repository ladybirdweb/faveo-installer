(function(window,document,undefined){'use strict';var ie10plus=window.navigator.msPointerEnabled;function Flow(opts){this.support=(typeof File!=='undefined'&&typeof Blob!=='undefined'&&typeof FileList!=='undefined'&&(!!Blob.prototype.slice||!!Blob.prototype.webkitSlice||!!Blob.prototype.mozSlice||!1));if(!this.support){return}
this.supportDirectory=/Chrome/.test(window.navigator.userAgent);this.files=[];this.defaults={chunkSize:1024*1024,forceChunkSize:!1,simultaneousUploads:3,singleFile:!1,fileParameterName:'file',progressCallbacksInterval:500,speedSmoothingFactor:0.1,query:{},headers:{},withCredentials:!1,preprocess:null,method:'multipart',testMethod:'GET',uploadMethod:'POST',prioritizeFirstAndLastChunk:!1,allowDuplicateUploads:!1,target:'/',testChunks:!0,generateUniqueIdentifier:null,maxChunkRetries:0,chunkRetryInterval:null,permanentErrors:[404,413,415,500,501],successStatuses:[200,201,202],onDropStopPropagation:!1,initFileFn:null,readFileFn:webAPIFileRead};this.opts={};this.events={};var $=this;this.onDrop=function(event){if($.opts.onDropStopPropagation){event.stopPropagation()}
event.preventDefault();var dataTransfer=event.dataTransfer;if(dataTransfer.items&&dataTransfer.items[0]&&dataTransfer.items[0].webkitGetAsEntry){$.webkitReadDataTransfer(event)}else{$.addFiles(dataTransfer.files,event)}};this.preventEvent=function(event){event.preventDefault()};this.opts=Flow.extend({},this.defaults,opts||{})}
Flow.prototype={on:function(event,callback){event=event.toLowerCase();if(!this.events.hasOwnProperty(event)){this.events[event]=[]}
this.events[event].push(callback)},off:function(event,fn){if(event!==undefined){event=event.toLowerCase();if(fn!==undefined){if(this.events.hasOwnProperty(event)){arrayRemove(this.events[event],fn)}}else{delete this.events[event]}}else{this.events={}}},fire:function(event,args){args=Array.prototype.slice.call(arguments);event=event.toLowerCase();var preventDefault=!1;if(this.events.hasOwnProperty(event)){each(this.events[event],function(callback){preventDefault=callback.apply(this,args.slice(1))===!1||preventDefault},this)}
if(event!='catchall'){args.unshift('catchAll');preventDefault=this.fire.apply(this,args)===!1||preventDefault}
return!preventDefault},webkitReadDataTransfer:function(event){var $=this;var queue=event.dataTransfer.items.length;var files=[];each(event.dataTransfer.items,function(item){var entry=item.webkitGetAsEntry();if(!entry){decrement();return}
if(entry.isFile){fileReadSuccess(item.getAsFile(),entry.fullPath)}else{readDirectory(entry.createReader())}});function readDirectory(reader){reader.readEntries(function(entries){if(entries.length){queue+=entries.length;each(entries,function(entry){if(entry.isFile){var fullPath=entry.fullPath;entry.file(function(file){fileReadSuccess(file,fullPath)},readError)}else if(entry.isDirectory){readDirectory(entry.createReader())}});readDirectory(reader)}else{decrement()}},readError)}
function fileReadSuccess(file,fullPath){file.relativePath=fullPath.substring(1);files.push(file);decrement()}
function readError(fileError){throw fileError}
function decrement(){if(--queue==0){$.addFiles(files,event)}}},generateUniqueIdentifier:function(file){var custom=this.opts.generateUniqueIdentifier;if(typeof custom==='function'){return custom(file)}
var relativePath=file.relativePath||file.webkitRelativePath||file.fileName||file.name;return file.size+'-'+relativePath.replace(/[^0-9a-zA-Z_-]/img,'')},uploadNextChunk:function(preventEvents){var found=!1;if(this.opts.prioritizeFirstAndLastChunk){each(this.files,function(file){if(!file.paused&&file.chunks.length&&file.chunks[0].status()==='pending'){file.chunks[0].send();found=!0;return!1}
if(!file.paused&&file.chunks.length>1&&file.chunks[file.chunks.length-1].status()==='pending'){file.chunks[file.chunks.length-1].send();found=!0;return!1}});if(found){return found}}
each(this.files,function(file){if(!file.paused){each(file.chunks,function(chunk){if(chunk.status()==='pending'){chunk.send();found=!0;return!1}})}
if(found){return!1}});if(found){return!0}
var outstanding=!1;each(this.files,function(file){if(!file.isComplete()){outstanding=!0;return!1}});if(!outstanding&&!preventEvents){async(function(){this.fire('complete')},this)}
return!1},assignBrowse:function(domNodes,isDirectory,singleFile,attributes){if(domNodes instanceof Element){domNodes=[domNodes]}
each(domNodes,function(domNode){var input;if(domNode.tagName==='INPUT'&&domNode.type==='file'){input=domNode}else{input=document.createElement('input');input.setAttribute('type','file');extend(input.style,{visibility:'hidden',position:'absolute',width:'1px',height:'1px'});domNode.appendChild(input);domNode.addEventListener('click',function(){input.click()},!1)}
if(!this.opts.singleFile&&!singleFile){input.setAttribute('multiple','multiple')}
if(isDirectory){input.setAttribute('webkitdirectory','webkitdirectory')}
each(attributes,function(value,key){input.setAttribute(key,value)});var $=this;input.addEventListener('change',function(e){if(e.target.value){$.addFiles(e.target.files,e);e.target.value=''}},!1)},this)},assignDrop:function(domNodes){if(typeof domNodes.length==='undefined'){domNodes=[domNodes]}
each(domNodes,function(domNode){domNode.addEventListener('dragover',this.preventEvent,!1);domNode.addEventListener('dragenter',this.preventEvent,!1);domNode.addEventListener('drop',this.onDrop,!1)},this)},unAssignDrop:function(domNodes){if(typeof domNodes.length==='undefined'){domNodes=[domNodes]}
each(domNodes,function(domNode){domNode.removeEventListener('dragover',this.preventEvent);domNode.removeEventListener('dragenter',this.preventEvent);domNode.removeEventListener('drop',this.onDrop)},this)},isUploading:function(){var uploading=!1;each(this.files,function(file){if(file.isUploading()){uploading=!0;return!1}});return uploading},_shouldUploadNext:function(){var num=0;var should=!0;var simultaneousUploads=this.opts.simultaneousUploads;each(this.files,function(file){each(file.chunks,function(chunk){if(chunk.status()==='uploading'){num++;if(num>=simultaneousUploads){should=!1;return!1}}})});return should&&num},upload:function(){var ret=this._shouldUploadNext();if(ret===!1){return}
this.fire('uploadStart');var started=!1;for(var num=1;num<=this.opts.simultaneousUploads-ret;num++){started=this.uploadNextChunk(!0)||started}
if(!started){async(function(){this.fire('complete')},this)}},resume:function(){each(this.files,function(file){file.resume()})},pause:function(){each(this.files,function(file){file.pause()})},cancel:function(){for(var i=this.files.length-1;i>=0;i--){this.files[i].cancel()}},progress:function(){var totalDone=0;var totalSize=0;each(this.files,function(file){totalDone+=file.progress()*file.size;totalSize+=file.size});return totalSize>0?totalDone/totalSize:0},addFile:function(file,event){this.addFiles([file],event)},addFiles:function(fileList,event){var files=[];each(fileList,function(file){if((!ie10plus||ie10plus&&file.size>0)&&!(file.size%4096===0&&(file.name==='.'||file.fileName==='.'))&&(this.opts.allowDuplicateUploads||!this.getFromUniqueIdentifier(this.generateUniqueIdentifier(file)))){var f=new FlowFile(this,file);if(this.fire('fileAdded',f,event)){files.push(f)}}},this);if(this.fire('filesAdded',files,event)){each(files,function(file){if(this.opts.singleFile&&this.files.length>0){this.removeFile(this.files[0])}
this.files.push(file)},this);this.fire('filesSubmitted',files,event)}},removeFile:function(file){for(var i=this.files.length-1;i>=0;i--){if(this.files[i]===file){this.files.splice(i,1);file.abort();this.fire('fileRemoved',file)}}},getFromUniqueIdentifier:function(uniqueIdentifier){var ret=!1;each(this.files,function(file){if(file.uniqueIdentifier===uniqueIdentifier){ret=file}});return ret},getSize:function(){var totalSize=0;each(this.files,function(file){totalSize+=file.size});return totalSize},sizeUploaded:function(){var size=0;each(this.files,function(file){size+=file.sizeUploaded()});return size},timeRemaining:function(){var sizeDelta=0;var averageSpeed=0;each(this.files,function(file){if(!file.paused&&!file.error){sizeDelta+=file.size-file.sizeUploaded();averageSpeed+=file.averageSpeed}});if(sizeDelta&&!averageSpeed){return Number.POSITIVE_INFINITY}
if(!sizeDelta&&!averageSpeed){return 0}
return Math.floor(sizeDelta/averageSpeed)}};function FlowFile(flowObj,file){this.flowObj=flowObj;this.bytes=null;this.file=file;this.name=file.fileName||file.name;this.size=file.size;this.relativePath=file.relativePath||file.webkitRelativePath||this.name;this.uniqueIdentifier=flowObj.generateUniqueIdentifier(file);this.chunks=[];this.paused=!1;this.error=!1;this.averageSpeed=0;this.currentSpeed=0;this._lastProgressCallback=Date.now();this._prevUploadedSize=0;this._prevProgress=0;this.bootstrap()}
FlowFile.prototype={measureSpeed:function(){var timeSpan=Date.now()-this._lastProgressCallback;if(!timeSpan){return}
var smoothingFactor=this.flowObj.opts.speedSmoothingFactor;var uploaded=this.sizeUploaded();this.currentSpeed=Math.max((uploaded-this._prevUploadedSize)/timeSpan*1000,0);this.averageSpeed=smoothingFactor*this.currentSpeed+(1-smoothingFactor)*this.averageSpeed;this._prevUploadedSize=uploaded},chunkEvent:function(chunk,event,message){switch(event){case 'progress':if(Date.now()-this._lastProgressCallback<this.flowObj.opts.progressCallbacksInterval){break}
this.measureSpeed();this.flowObj.fire('fileProgress',this,chunk);this.flowObj.fire('progress');this._lastProgressCallback=Date.now();break;case 'error':this.error=!0;this.abort(!0);this.flowObj.fire('fileError',this,message,chunk);this.flowObj.fire('error',message,this,chunk);break;case 'success':if(this.error){return}
this.measureSpeed();this.flowObj.fire('fileProgress',this,chunk);this.flowObj.fire('progress');this._lastProgressCallback=Date.now();if(this.isComplete()){this.currentSpeed=0;this.averageSpeed=0;this.flowObj.fire('fileSuccess',this,message,chunk)}
break;case 'retry':this.flowObj.fire('fileRetry',this,chunk);break}},pause:function(){this.paused=!0;this.abort()},resume:function(){this.paused=!1;this.flowObj.upload()},abort:function(reset){this.currentSpeed=0;this.averageSpeed=0;var chunks=this.chunks;if(reset){this.chunks=[]}
each(chunks,function(c){if(c.status()==='uploading'){c.abort();this.flowObj.uploadNextChunk()}},this)},cancel:function(){this.flowObj.removeFile(this)},retry:function(){this.bootstrap();this.flowObj.upload()},bootstrap:function(){if(typeof this.flowObj.opts.initFileFn==="function"){this.flowObj.opts.initFileFn(this)}
this.abort(!0);this.error=!1;this._prevProgress=0;var round=this.flowObj.opts.forceChunkSize?Math.ceil:Math.floor;var chunks=Math.max(round(this.size/this.flowObj.opts.chunkSize),1);for(var offset=0;offset<chunks;offset++){this.chunks.push(new FlowChunk(this.flowObj,this,offset))}},progress:function(){if(this.error){return 1}
if(this.chunks.length===1){this._prevProgress=Math.max(this._prevProgress,this.chunks[0].progress());return this._prevProgress}
var bytesLoaded=0;each(this.chunks,function(c){bytesLoaded+=c.progress()*(c.endByte-c.startByte)});var percent=bytesLoaded/this.size;this._prevProgress=Math.max(this._prevProgress,percent>0.9999?1:percent);return this._prevProgress},isUploading:function(){var uploading=!1;each(this.chunks,function(chunk){if(chunk.status()==='uploading'){uploading=!0;return!1}});return uploading},isComplete:function(){var outstanding=!1;each(this.chunks,function(chunk){var status=chunk.status();if(status==='pending'||status==='uploading'||status==='reading'||chunk.preprocessState===1||chunk.readState===1){outstanding=!0;return!1}});return!outstanding},sizeUploaded:function(){var size=0;each(this.chunks,function(chunk){size+=chunk.sizeUploaded()});return size},timeRemaining:function(){if(this.paused||this.error){return 0}
var delta=this.size-this.sizeUploaded();if(delta&&!this.averageSpeed){return Number.POSITIVE_INFINITY}
if(!delta&&!this.averageSpeed){return 0}
return Math.floor(delta/this.averageSpeed)},getType:function(){return this.file.type&&this.file.type.split('/')[1]},getExtension:function(){return this.name.substr((~-this.name.lastIndexOf(".")>>>0)+2).toLowerCase()}};function webAPIFileRead(fileObj,startByte,endByte,fileType,chunk){var function_name='slice';if(fileObj.file.slice)
function_name='slice';else if(fileObj.file.mozSlice)
function_name='mozSlice';else if(fileObj.file.webkitSlice)
function_name='webkitSlice';chunk.readFinished(fileObj.file[function_name](startByte,endByte,fileType))}
function FlowChunk(flowObj,fileObj,offset){this.flowObj=flowObj;this.fileObj=fileObj;this.offset=offset;this.tested=!1;this.retries=0;this.pendingRetry=!1;this.preprocessState=0;this.readState=0;this.loaded=0;this.total=0;this.chunkSize=this.flowObj.opts.chunkSize;this.startByte=this.offset*this.chunkSize;this.computeEndByte=function(){var endByte=Math.min(this.fileObj.size,(this.offset+1)*this.chunkSize);if(this.fileObj.size-endByte<this.chunkSize&&!this.flowObj.opts.forceChunkSize){endByte=this.fileObj.size}
return endByte}
this.endByte=this.computeEndByte();this.xhr=null;var $=this;this.event=function(event,args){args=Array.prototype.slice.call(arguments);args.unshift($);$.fileObj.chunkEvent.apply($.fileObj,args)};this.progressHandler=function(event){if(event.lengthComputable){$.loaded=event.loaded;$.total=event.total}
$.event('progress',event)};this.testHandler=function(event){var status=$.status(!0);if(status==='error'){$.event(status,$.message());$.flowObj.uploadNextChunk()}else if(status==='success'){$.tested=!0;$.event(status,$.message());$.flowObj.uploadNextChunk()}else if(!$.fileObj.paused){$.tested=!0;$.send()}};this.doneHandler=function(event){var status=$.status();if(status==='success'||status==='error'){delete this.data;$.event(status,$.message());$.flowObj.uploadNextChunk()}else{$.event('retry',$.message());$.pendingRetry=!0;$.abort();$.retries++;var retryInterval=$.flowObj.opts.chunkRetryInterval;if(retryInterval!==null){setTimeout(function(){$.send()},retryInterval)}else{$.send()}}}}
FlowChunk.prototype={getParams:function(){return{flowChunkNumber:this.offset+1,flowChunkSize:this.flowObj.opts.chunkSize,flowCurrentChunkSize:this.endByte-this.startByte,flowTotalSize:this.fileObj.size,flowIdentifier:this.fileObj.uniqueIdentifier,flowFilename:this.fileObj.name,flowRelativePath:this.fileObj.relativePath,flowTotalChunks:this.fileObj.chunks.length}},getTarget:function(target,params){if(target.indexOf('?')<0){target+='?'}else{target+='&'}
return target+params.join('&')},test:function(){this.xhr=new XMLHttpRequest();this.xhr.addEventListener("load",this.testHandler,!1);this.xhr.addEventListener("error",this.testHandler,!1);var testMethod=evalOpts(this.flowObj.opts.testMethod,this.fileObj,this);var data=this.prepareXhrRequest(testMethod,!0);this.xhr.send(data)},preprocessFinished:function(){this.endByte=this.computeEndByte();this.preprocessState=2;this.send()},readFinished:function(bytes){this.readState=2;this.bytes=bytes;this.send()},send:function(){var preprocess=this.flowObj.opts.preprocess;var read=this.flowObj.opts.readFileFn;if(typeof preprocess==='function'){switch(this.preprocessState){case 0:this.preprocessState=1;preprocess(this);return;case 1:return}}
switch(this.readState){case 0:this.readState=1;read(this.fileObj,this.startByte,this.endByte,this.fileObj.file.type,this);return;case 1:return}
if(this.flowObj.opts.testChunks&&!this.tested){this.test();return}
this.loaded=0;this.total=0;this.pendingRetry=!1;this.xhr=new XMLHttpRequest();this.xhr.upload.addEventListener('progress',this.progressHandler,!1);this.xhr.addEventListener("load",this.doneHandler,!1);this.xhr.addEventListener("error",this.doneHandler,!1);var uploadMethod=evalOpts(this.flowObj.opts.uploadMethod,this.fileObj,this);var data=this.prepareXhrRequest(uploadMethod,!1,this.flowObj.opts.method,this.bytes);this.xhr.send(data)},abort:function(){var xhr=this.xhr;this.xhr=null;if(xhr){xhr.abort()}},status:function(isTest){if(this.readState===1){return'reading'}else if(this.pendingRetry||this.preprocessState===1){return'uploading'}else if(!this.xhr){return'pending'}else if(this.xhr.readyState<4){return'uploading'}else{if(this.flowObj.opts.successStatuses.indexOf(this.xhr.status)>-1){return'success'}else if(this.flowObj.opts.permanentErrors.indexOf(this.xhr.status)>-1||!isTest&&this.retries>=this.flowObj.opts.maxChunkRetries){return'error'}else{this.abort();return'pending'}}},message:function(){return this.xhr?this.xhr.responseText:''},progress:function(){if(this.pendingRetry){return 0}
var s=this.status();if(s==='success'||s==='error'){return 1}else if(s==='pending'){return 0}else{return this.total>0?this.loaded/this.total:0}},sizeUploaded:function(){var size=this.endByte-this.startByte;if(this.status()!=='success'){size=this.progress()*size}
return size},prepareXhrRequest:function(method,isTest,paramsMethod,blob){var query=evalOpts(this.flowObj.opts.query,this.fileObj,this,isTest);query=extend(query,this.getParams());var target=evalOpts(this.flowObj.opts.target,this.fileObj,this,isTest);var data=null;if(method==='GET'||paramsMethod==='octet'){var params=[];each(query,function(v,k){params.push([encodeURIComponent(k),encodeURIComponent(v)].join('='))});target=this.getTarget(target,params);data=blob||null}else{data=new FormData();each(query,function(v,k){data.append(k,v)});data.append(this.flowObj.opts.fileParameterName,blob,this.fileObj.file.name)}
this.xhr.open(method,target,!0);this.xhr.withCredentials=this.flowObj.opts.withCredentials;each(evalOpts(this.flowObj.opts.headers,this.fileObj,this,isTest),function(v,k){this.xhr.setRequestHeader(k,v)},this);return data}};function arrayRemove(array,value){var index=array.indexOf(value);if(index>-1){array.splice(index,1)}}
function evalOpts(data,args){if(typeof data==="function"){args=Array.prototype.slice.call(arguments);data=data.apply(null,args.slice(1))}
return data}
Flow.evalOpts=evalOpts;function async(fn,context){setTimeout(fn.bind(context),0)}
function extend(dst,src){each(arguments,function(obj){if(obj!==dst){each(obj,function(value,key){dst[key]=value})}});return dst}
Flow.extend=extend;function each(obj,callback,context){if(!obj){return}
var key;if(typeof(obj.length)!=='undefined'){for(key=0;key<obj.length;key++){if(callback.call(context,obj[key],key)===!1){return}}}else{for(key in obj){if(obj.hasOwnProperty(key)&&callback.call(context,obj[key],key)===!1){return}}}}
Flow.each=each;Flow.FlowFile=FlowFile;Flow.FlowChunk=FlowChunk;Flow.version='2.11.2';if(typeof module==="object"&&module&&typeof module.exports==="object"){module.exports=Flow}else{window.Flow=Flow;if(typeof define==="function"&&define.amd){define("flow",[],function(){return Flow})}}})(window,document);angular.module('flow.provider',[]).provider('flowFactory',function(){'use strict';this.defaults={};this.factory=function(options){return new Flow(options)};this.events=[];this.on=function(event,callback){this.events.push([event,callback])};this.$get=function(){var fn=this.factory;var defaults=this.defaults;var events=this.events;return{'create':function(opts){var flow=fn(angular.extend({},defaults,opts));angular.forEach(events,function(event){flow.on(event[0],event[1])});return flow}}}});angular.module('flow.init',['flow.provider']).controller('flowCtrl',['$scope','$attrs','$parse','flowFactory',function($scope,$attrs,$parse,flowFactory){var options=angular.extend({},$scope.$eval($attrs.flowInit));var flow=$scope.$eval($attrs.flowObject)||flowFactory.create(options);var catchAllHandler=function(eventName){var args=Array.prototype.slice.call(arguments);args.shift();var event=$scope.$broadcast.apply($scope,['flow::'+eventName,flow].concat(args));if({'progress':1,'filesSubmitted':1,'fileSuccess':1,'fileError':1,'complete':1}[eventName]){$scope.$apply()}
if(event.defaultPrevented){return!1}};flow.on('catchAll',catchAllHandler);$scope.$on('$destroy',function(){flow.off('catchAll',catchAllHandler)});$scope.$flow=flow;if($attrs.hasOwnProperty('flowName')){$parse($attrs.flowName).assign($scope,flow);$scope.$on('$destroy',function(){$parse($attrs.flowName).assign($scope)})}}]).directive('flowInit',[function(){return{scope:!0,controller:'flowCtrl'}}]);angular.module('flow.btn',['flow.init']).directive('flowBtn',[function(){return{'restrict':'EA','scope':!1,'require':'^flowInit','link':function(scope,element,attrs){var isDirectory=attrs.hasOwnProperty('flowDirectory');var isSingleFile=attrs.hasOwnProperty('flowSingleFile');var inputAttrs=attrs.hasOwnProperty('flowAttrs')&&scope.$eval(attrs.flowAttrs);scope.$flow.assignBrowse(element,isDirectory,isSingleFile,inputAttrs)}}}]);angular.module('flow.dragEvents',['flow.init']).directive('flowPreventDrop',function(){return{'scope':!1,'link':function(scope,element,attrs){element.bind('drop dragover',function(event){event.preventDefault()})}}}).directive('flowDragEnter',['$timeout',function($timeout){return{'scope':!1,'link':function(scope,element,attrs){var promise;var enter=!1;element.bind('dragover',function(event){if(!isFileDrag(event)){return}
if(!enter){scope.$apply(attrs.flowDragEnter);enter=!0}
$timeout.cancel(promise);event.preventDefault()});element.bind('dragleave drop',function(event){$timeout.cancel(promise);promise=$timeout(function(){scope.$eval(attrs.flowDragLeave);promise=null;enter=!1},100)});function isFileDrag(dragEvent){var fileDrag=!1;var dataTransfer=dragEvent.dataTransfer||dragEvent.originalEvent.dataTransfer;angular.forEach(dataTransfer&&dataTransfer.types,function(val){if(val==='Files'){fileDrag=!0}});return fileDrag}}}}]);angular.module('flow.drop',['flow.init']).directive('flowDrop',function(){return{'scope':!1,'require':'^flowInit','link':function(scope,element,attrs){if(attrs.flowDropEnabled){scope.$watch(attrs.flowDropEnabled,function(value){if(value){assignDrop()}else{unAssignDrop()}})}else{assignDrop()}
function assignDrop(){scope.$flow.assignDrop(element)}
function unAssignDrop(){scope.$flow.unAssignDrop(element)}}}});!function(angular){'use strict';var module=angular.module('flow.events',['flow.init']);var events={fileSuccess:['$file','$message'],fileProgress:['$file'],fileAdded:['$file','$event'],filesAdded:['$files','$event'],filesSubmitted:['$files','$event'],fileRetry:['$file'],fileError:['$file','$message'],uploadStart:[],complete:[],progress:[],error:['$message','$file']};angular.forEach(events,function(eventArgs,eventName){var name='flow'+capitaliseFirstLetter(eventName);if(name=='flowUploadStart'){name='flowUploadStarted'}
module.directive(name,[function(){return{require:'^flowInit',controller:['$scope','$attrs',function($scope,$attrs){$scope.$on('flow::'+eventName,function(){var funcArgs=Array.prototype.slice.call(arguments);var event=funcArgs.shift();if($scope.$flow!==funcArgs.shift()){return}
var args={};angular.forEach(eventArgs,function(value,key){args[value]=funcArgs[key]});if($scope.$eval($attrs[name],args)===!1){event.preventDefault()}})}]}}])});function capitaliseFirstLetter(string){return string.charAt(0).toUpperCase()+string.slice(1)}}(angular);angular.module('flow.img',['flow.init']).directive('flowImg',[function(){return{'scope':!1,'require':'^flowInit','link':function(scope,element,attrs){var file=attrs.flowImg;scope.$watch(file,function(file){if(!file){return}
var fileReader=new FileReader();fileReader.readAsDataURL(file.file);fileReader.onload=function(event){scope.$apply(function(){attrs.$set('src',event.target.result)})}})}}}]);angular.module('flow.transfers',['flow.init']).directive('flowTransfers',[function(){return{'scope':!0,'require':'^flowInit','link':function(scope){scope.transfers=scope.$flow.files}}}]);angular.module('flow',['flow.provider','flow.init','flow.events','flow.btn','flow.drop','flow.transfers','flow.img','flow.dragEvents'])