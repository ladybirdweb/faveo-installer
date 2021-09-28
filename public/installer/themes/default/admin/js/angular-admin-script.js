var commonPath=localStorage.getItem('PATH');
//change Language
function changeLang(lang) {
        var link = commonPath+'swtich-language/'+lang;
        window.location = link;
    }


 $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
    });

//slideUp Function
$(document).ready(function(){
        
        if($('.alert-success, .alert-danger').html()){
              
                setInterval(function(){
                    $('.alert-success').slideUp( 3000, function() {});
                }, 2000);
            }
        
    })

//NProgress
NProgress.configure({ showSpinner: false });
NProgress.start();
$(document).ready(function () {
         $(document).ajaxStart(function() {
                    NProgress.start();
                 });

                $(document).ajaxComplete(function() {
                    NProgress.done();
                });
             $('.sidebar-toggle').click(function () {
                   $('.main-sidebar').toggle();;
             });
    });
$(window).on('load',function(){
     setTimeout(function(){
      NProgress.done();
    },3500);
  })

//ckeditor
  if($('#ckeditor')[0]){
      CKEDITOR.replace('ckeditor', {
              toolbarGroups: [
                {"name": "basicstyles", "groups": ["basicstyles"]},
                {"name": "links", "groups": ["links"]},
                {"name": "paragraph", "groups": ["list", "blocks"]},
                {"name": "document", "groups": ["mode"]},
                {"name": "insert", "groups": ["insert"]},
                {"name": "styles", "groups": ["styles"]},
                {"name": "colors", "groups": ["TextColor", "BGColor"]}
            ],
            removeButtons: 'Underline,Strike,Subscript,Superscript,Anchor,Styles,Specialchar,Image',
            removePlugins: 'liststyle,tabletools,scayt,menubutton,contextmenu,wsc',
            disableNativeSpellChecker: false
            }).on('change',function(){
                $('#submit').removeAttr('disabled');
                $('#form_submit').removeAttr('disabled');
                
            });
        CKEDITOR.config.scayt_autoStartup = true;
        CKEDITOR.config.allowedContent = true;
        CKEDITOR.config.extraPlugins = 'font,bidi,colorbutton,autolink,colordialog';
        CKEDITOR.config.menu_groups = 
    'tablecell,tablecellproperties,tablerow,tablecolumn,table,' +
    'anchor,link,image,flash';
}

//checkbox function

                    $(function() {
                        $(".checkbox-toggle").click(function() {
                        var clicks = $(this).data('clicks');
                        if (clicks) {
                            //Uncheck all checkboxes
                            $("input[type='checkbox']", ".mailbox-messages").iCheck("uncheck");
                        } else {
                            //Check all checkboxes
                            $("input[type='checkbox']", ".mailbox-messages").iCheck("check");
                        }
                        $(this).data("clicks", !clicks);
                    });
                            //Handle starring for glyphicon and font awesome
                            $(".mailbox-star").click(function(e) {
                    e.preventDefault();
                            //detect type
                            var $this = $(this).find("a > i");
                            var glyph = $this.hasClass("glyphicon");
                            var fa = $this.hasClass("fa");
                            //Switch states
                            if (glyph) {
                    $this.toggleClass("glyphicon-star");
                            $this.toggleClass("glyphicon-star-empty");
                    }
                    if (fa) {
                    $this.toggleClass("fa-star");
                            $this.toggleClass("fa-star-o");
                    }
                    });
                    });

//

    

        $('#read-all').click(function () {

            var id2 = "<?php echo \Auth::user()->id ?>";
            var dataString = 'id=' + id2;
            $.ajax
                    ({
                        type: "POST",
                        url: commonPath+'mark-all-read/' + id2,
                        data: dataString,
                        cache: false,
                        beforeSend: function () {
                            $('.loader').css('display','block');
                            $('#myDropdown').on('hide.bs.dropdown', function () {
                                return false;
                            });
                            $("#refreshNote").hide();
                            $("#notification-loader").show();
                        },
                        success: function (response) {
                            $('.loader').css('display','none');
                            $("#refreshNote").load("<?php echo $_SERVER['REQUEST_URI']; ?>  #refreshNote");
                            $("#notification-loader").hide();
                            $('#myDropdown').removeClass('open');
                        }
                    });
        });

//Helpsection Function
function sidebaropen(e){
         var link=$(e).attr('href');
         localStorage.setItem('url',link);
    }
    function removeurl(){
        localStorage.removeItem('url')
    }
    function helpTab(){
     $('.nav-tabs li a[href="#home-tab"]').tab('show');
};
function mailTab(){
    $('.nav-tabs li a[href="#settings-tab"]').tab('show');
}

function openSlide(){
if ( document.getElementById("right").className.match(/(?:^|\s)right-side-menu control-sidebar-dark(?!\S)/) ){
         document.getElementById("right").className += "right-side-menu control-sidebar-dark right-side-menu-open";
    if(navigator.userAgent.indexOf('Mac')>0){
       $('.right-side-menu').css('right','-82px'); 
    }
}
else{
     document.getElementById("right").className = "right-side-menu control-sidebar-dark";
     if(navigator.userAgent.indexOf('Mac')>0){
        $('.right-side-menu').css('right','')
    }
}
if ( document.getElementById("right1").className.match(/(?:^|\s)right-side-menu1 control-sidebar-dark(?!\S)/) ){
         document.getElementById("right1").className += "right-side-menu1 control-sidebar-dark right-side-menu-open1";
}
else{
     document.getElementById("right1").className = "right-side-menu1 control-sidebar-dark";
}
}

function bytesToSize(bytes) {
                            var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                            if (bytes == 0) return '';
                            var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
                            return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
};
function gcd (a, b) {
                             return (b == 0) ? a : gcd (b, a%b);
                        };


$(".box-primary").on('change',".file-data", function(){
  
            if(this.files[0].size > 2048*1024){
                $(this).parent().find(".file-error").empty()
                $(this).parent().append("<p class='file-error' style='color:red'>cannot upload files more than 2 MB</p>")
                $(this).val('');
            }
        else{
            $(this).parent().find(".file-error").empty()
        }
   });


 $(function(){
    $('#submit').attr('disabled','disabled');
    $('#Form').on('input',function(){
        $('#submit').removeAttr('disabled');
    });
    $('#Form').on('change',':input',function(){
        $('#submit').removeAttr('disabled');
    });
    });
   

   //Plugin Check
    var plugin = localStorage.getItem('PLUGIN');
             if(plugin==1){
                 $('.content-header').prevUntil('.tab-content').remove();
                 
                     var path = localStorage.getItem('url');
            $(document).ready(function() {
                $('.sidebar-menu li').filter(function(){
                        
                        if($('a', this).attr('href') === path){
                                $(this).addClass('active');
                              
                             if(this.parentNode.className=="treeview-menu"){
                                    $(this.parentNode).addClass('menu-open');
                                    $(this.parentNode).css('display','block');
                                    $('.sidebar').animate({scrollTop:250+"px"}, 500);
                                };
                              
                        }
                      })
                     
                 });
                 
             }


//Angular Script
 var app = angular.module('fbApp', ['frapontillo.bootstrap-switch','angularMoment','flow','ngFileUpload','ui.tree','ui.bootstrap','ngDesktopNotification','tw-currency-select']).directive('whenScrolled', function() {
    return function(scope, elm, attr) {
        var raw = elm[0];
        elm.bind('scroll', function() {

            if (raw.scrollTop + raw.offsetHeight >= raw.scrollHeight) {
                scope.$apply(attr.whenScrolled);
            }
        });
    };
});
app.factory('httpInterceptor', ['$q', '$rootScope',
    function ($q, $rootScope) {
        var loadingCount = 0;

        return {
            request: function (config) {
                if(++loadingCount === 1) $rootScope.$broadcast('loading:progress');
                return config || $q.when(config);
            },

            response: function (response) {
                if(--loadingCount === 0) $rootScope.$broadcast('loading:finish');
                return response || $q.when(response);
            },

            responseError: function (response) {
                if(--loadingCount === 0) $rootScope.$broadcast('loading:finish');
                return $q.reject(response);
            }
        };
    }
]).config(['$httpProvider', function ($httpProvider) {
    $httpProvider.interceptors.push('httpInterceptor');
}]);
app.directive('mediaLibScrolled', function() {
    return function(scope, elm, attr) {
        var raw = elm[0];
        elm.bind('scroll', function() {

            if (raw.scrollTop + raw.offsetHeight >= raw.scrollHeight) {
                scope.$apply(attr.mediaLibScrolled);
            }
        });
    };
});
app.directive('numbersOnly', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attr, ngModelCtrl) {
            function fromUser(text) {
                if (text) {
                    var transformedInput = text.replace(/[^0-9]/g, '');

                    if (transformedInput !== text) {
                        ngModelCtrl.$viewValue=0;
                        ngModelCtrl.$render();
                    }
                    return transformedInput;
                }
                return undefined;
            }            
            ngModelCtrl.$parsers.push(fromUser);
        }
    };
});
var fileProcess=null;
app.config(['flowFactoryProvider', function (flowFactoryProvider,$scope,$http) {
    fileProcess=flowFactoryProvider;
    
    flowFactoryProvider.on('fileError', function (file,message) {
        $('#errorFileMsg').css('display','block');
    });
   
    flowFactoryProvider.factory = fustyFlowFactory;
  }]);
app.run(function($http,$rootScope) {
    $rootScope.$on('loading:progress', function (){
        NProgress.start();
    });

    $rootScope.$on('loading:finish', function (){
        NProgress.done();
    });
            $rootScope.arrayImage=[];
       fileProcess.on('fileSuccess', function (file,message) {
        if($('#errorFileMsg').css('display')=="block"){
           $('#errorFileMsg').css('display','none');
        }
        $('#mytabs a[href="#menu1"]').tab('show');
        $("#progressHide").hide();
        $("#progressHide").find('.transfer-box').remove();
        $http.get(commonPath+"media/files").success(function(data){
                
                $rootScope.arrayImage=data;

                $rootScope.disable=false;
                $rootScope.preview=true;
           $rootScope.viewImage=$rootScope.arrayImage.data[0]
                     if($rootScope.arrayImage.data[0].type=="image"){
               var r = gcd ($rootScope.arrayImage.data[0].width, $rootScope.arrayImage.data[0].height);
               $rootScope.widthRatio=$rootScope.arrayImage.data[0].width/r;
               $rootScope.heightRatio=$rootScope.arrayImage.data[0].height/r;
               $rootScope.mediaWidth=$rootScope.arrayImage.data[0].width;
               $rootScope.mediaHeight=$rootScope.arrayImage.data[0].height;
               $rootScope.mediaImage={'width':$rootScope.arrayImage.data[0].width,'height':$rootScope.arrayImage.data[0].height};
               $rootScope.inlineImage=false;
               $rootScope.viewImage=$rootScope.arrayImage.data[0].base_64;
               $rootScope.pathName=$rootScope.arrayImage.data[0].pathname;
               $rootScope.fileName=$rootScope.arrayImage.data[0].filename;
               $rootScope.fileSize=bytesToSize($rootScope.arrayImage.data[0].size);
               $rootScope.privewSize=true;
               $rootScope.modifiedMedia=$rootScope.arrayImage.data[0].modified;
           }
           else if($rootScope.arrayImage.data[0].type=="text"){
               $rootScope.inlineImage=true;
               $rootScope.viewImage=commonPath+"lb-faveo/media/images/txt.png";
               $rootScope.pathName=$rootScope.arrayImage.data[0].pathname;
               $rootScope.fileName=$rootScope.arrayImage.data[0].filename;
               $rootScope.fileSize=bytesToSize($rootScope.arrayImage.data[0].size);
               $rootScope.privewSize=false;
               $rootScope.modifiedMedia=$rootScope.arrayImage.data[0].modified;
           }
           else{
               $rootScope.inlineImage=true;
               $rootScope.viewImage=commonPath+"lb-faveo/media/images/common.png";
               $rootScope.pathName=$rootScope.arrayImage.data[0].pathname;
               $rootScope.fileName=$rootScope.arrayImage.data[0].filename;
               $rootScope.fileSize=bytesToSize($rootScope.arrayImage.data[0].size);
               $rootScope.privewSize=false;
               $rootScope.modifiedMedia=$rootScope.arrayImage.data[0].modified;
           }
            
            setTimeout(function(){
                 $('label[for="happy0"]>img').css({'border': '1px solid #fff','box-shadow': '0 0 0 4px #0073aa'});
                 $('#happy0').prop( "checked", true );
                 $('label[for="happy1"]>img').css({'border': 'none','box-shadow': 'none'});
                 $('.image-view').animate({scrollTop: 0}, 500);
           },100)
        })
        
    });
    
});
  app.controller('helptopicCtrl', function($scope, Upload,$compile,desktopNotification,$http){
var inAppEnable=localStorage.getItem("NOTI_COND");
    if(inAppEnable=='1'){
        activate();
}
        function activate() {
            $scope.isSupported = desktopNotification.isSupported();
            $scope.permission = desktopNotification.currentPermission();
            $scope.autoClose = false;
            if($scope.permission=="granted"){
            var user =  localStorage.getItem("GUEST");
            // in app notification
            if(!user){
                var user_id = "<?php if(Auth::user()){ echo Auth::user()->hash_ids;} ?>";
                var user_role = "<?php if(Auth::user()){ echo Auth::user()->role;} ?>";
                var user_name = "<?php if(Auth::user()){ echo Auth::user()->user_name;} ?>";
                $scope.user_data =  "user="+user_id + "&user_agent=" + navigator.userAgent + "&browser_name=" + browserName + "&version="+fullVersion + "&platform=" + navigator.platform;
                $.ajax({
                    type: 'GET',
                    url: commonPath+"subscribe-user",
                    data: $scope.user_data,
                    success: function(data){
                        if(data.success){
                             getNotification()
                        }
                    }
                })
            }
        }
    }
    function getNotification(){
        setInterval(function() {
                    $http({
                        method: 'GET',
                        url: commonPath+"get-push-notifications?"+$scope.user_data
                    }).success(function(data){
                            NProgress.done();
                            $.each(data, function(key, value){
                                desktopNotification.show(value.message, {
                                    icon: 'https://secure.gravatar.com/avatar/153664630910409be4e20d0a747a23bc?s=80&r=g&d=identicon',
                                    body: value.created_at,
                                    autoClose:$scope.autoClose,
                                    onClick:function() {
                                        window.open(value.url,'_blank');
                                    }
                                });
                                closeNotification(value.id);
                            })
                    })

        },10000)
    };
    function closeNotification(x){
                    $http({
                        method: 'GET',
                        url: commonPath+"notification-delivered/"+x
                    }).success(function(data){

                    })
    };
                               $scope.fileArray = [];
                                    // Add events
                                    $('input[type=file]').on('change', prepareUpload);
                                    // Grab the files and set them to our variable
                                            function prepareUpload(event)
                                            {
                                            $scope.files = event.target.files;
                                                    for (var i = 0; i < $scope.files.length; i++) {
                                            $scope.fileArray.push($scope.files[i])
                                                   //console.log($scope.files[i]);
                                                   $compile($('.uploadFiles').append('<p id="files'+i+'" style="color: #0faaea;">'+$scope.files[i].name+'&nbsp<i class="fa fa-times-circle-o" aria-hidden="true" style="color: #d50404;" ng-click="removeUploadFile('+i+')"></i></P>'))($scope);
                                            }
                                            }


                                    $scope.removeUploadFile=function(x){
                                        $scope.fileArray.splice(x,1);
                                        $('#files'+x).remove();
                                    }
                                    $scope.helpForm = function() {
                                        $('.loader').css('display', 'block');
                                    $scope.submitData = {};
                                            $scope.submitData['help_email'] = $('#help_email').val();
                                            $scope.submitData['help_subject'] = $('#help_subject').val();
                                            $scope.submitData['help_massage'] = $('#help_massage').val();
                                            $scope.submitData['helpdocs'] = $scope.fileArray;
                                            $scope.submitData['_token'] = localStorage.getItem('CSRF');
                                            $('#myButton1').hide();
                                            $('.load').addClass('loading');
                                            setTimeout(function () {
                                            $('.load').removeClass('loading');
                                                    $('#myButton1').show();
                                            }, 5000);
                                            $scope.fileArray.upload = Upload.upload({
                                            url: commonPath+"helpsection/mail",
                                                    data: $scope.submitData,
                                            }).success(function(data){
                                                $('.loader').css('display', 'none');
                                            if(typeof data=='object'){
                                                $('.error-msg').html(data.fails);
                                                $('.help-danger').css('display', 'block');
                                            }
                                            else{
                                               $('.success-msg').html(data); 
                                               $('.help-success').css('display', 'block'); 
                                            }
                                            setInterval(function(){
                                                $('.alert').slideUp( 3000, function() {});
                                             }, 2000);
                                            
                                            $('#help_subject').val('');
                                            $('#help_massage').val('');
                                            // $('#helpForms').on('input',function(){
                                            $(document).ready(function(){
                                    var subject = $('#help_subject').val();
                                            var massage = $('#help_massage').val();
                                            if (subject != "" && massage != ""){
                                    $('#myButton1').removeAttr('disabled');
                                    }
                                    else{
                                    $('#myButton1').attr('disabled', 'disabled');
                                    }
                                    });
                                            // setInterval(function(){
                                            //     $('.alert-success').slideUp( 3000, function() {});
                                            // }, 500);




                                            // console.log(data);
                                    })
                                    }
                                    })

//Arabic language
 $(function () {
       if(localStorage.getItem("LANGUAGE")=='ar'){

         var adminRtl = document.createElement('link');
        adminRtl.id = 'id-rtl';
        adminRtl.rel = 'stylesheet';
        adminRtl.href = commonPath+"themes/default/common/adminlte3/rtl/adminlte3.min.css";
        document.head.appendChild(adminRtl);

        var cssRtl = document.createElement('link');
        cssRtl.id = 'id-csstrtl';
        cssRtl.rel = 'stylesheet';
        cssRtl.href = commonPath+"themes/default/common/css/rtl/css/rtl.min.css";
        document.head.appendChild(cssRtl);

        var bootRtl = document.createElement('link');
        bootRtl.id = 'id-bootrtl';
        bootRtl.rel = 'stylesheet';
        bootRtl.href = commonPath+"themes/default/common/css/rtl/css/bootstrap-rtl.min.css";

        $('.sidebar-mini').addClass("rtl");
        
        $('.content-wrapper').css('margin-left','unset');
        
        $('.main-header').css('margin-left','unset');
        
        $('.main-footer').css('margin-left','unset');

        $('.dataTables_paginate').find('.row').attr('dir','RTL');
        $('.dropdown-menu-right').removeClass("dropdown-menu-left");
        $('.dropdown-menu-left').addClass("dropdown-menu-right");

        $('.iframe').attr('dir','RTL');
         $('.user-panel .image').css('padding-right','0.8rem');
         $('.cke_contents_ltr ').attr('dir','RTL');
         $('.mailbox-attachments').css('margin-left','77%');
        
        $('#subButton').css('text-align','left')
     setTimeout(function(){  
        var sidebarHeight=(screen.height-178)+'px';
        $('#sideMenu').css({'overflow-y':'auto','height':sidebarHeight});
        $('#cke_details').addClass( "cke_rtl" );
        $('.datepicker').css('direction','rtl')
         $('.datepicker.dropdown-menu').css('right','initial')
        }, 3000);
        $('iframe').contents().find("body").attr('dir','RTL');
        $('#category').find('h3').html('حقول التذاكر (اسحب هنا)');
        $('#content1').find('h3').html('حقول التذاكر (إسقاط هنا)');
         setTimeout(function(){
           $('.tooltip1').find('a>span').prepend('&rlm;');
           $('.cke_ltr').attr('dir','rtl');
           $('.cke_ltr').toggleClass('cke_rtl');
         
       },500);
        setTimeout(function(){
          $('body').css('display','block');

       },300);
       } 
               else{
                   $('body').css('display','block');
               }
    });