@extends('layouts.app')

@section('content')

<script type="text/javascript">

$(document).ready(function(){

      var submit = $("#submit");
      var scandetails = $("#scan-details");
      var scanform = $("#scan-form");
      var scanloader = $("#scan-loader");
      var scanh3 = $("#scan-h3");

      function js_traverse(o) {
        var type = typeof o;
     

        if (type == "object") {
            for (var key in o) {
                   var details_content = document.createElement("div");

                        
                        if(key ==="0" || key === "string" || key === "plugins" || key ==="module"){
                          
                        }else{
                            details_content.innerHTML =  "Scanned:: " + key;
                            details_content.className = "details_content";

                            document.getElementById("details").appendChild(details_content);
                            
                        }
                         js_traverse(o[key]); 
                           
                
            }

        }

        if(type !== "object"){
           // console.log("Value: " +o);
            //document.getElementById("country").appendChild(document.createTextNode("Value: "+ o));
                var value = document.createElement("div");
                value.innerHTML =  "Detected:: " + o ;
                value.className = "value";
                value.style.borderBottom = "1px solid lightgrey";
                document.getElementById("details").appendChild(value);


            
        }
    } 



function isUrl(s) {
   var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
   return regexp.test(s);
}  


function xss_vector_map(){
  return entityMap = {
  '&': '&amp;',
  '<': '&lt;',
  '>': '&gt;',
  '"': '&quot;',
  "'": '&#39;',
  '/': '&#x2F;',
  '`': '&#x60;',
  '=': '&#x3D;'
};
}


function escapeHtml (string) {
  return String(string).replace(/[&<>"'`=\/]/g, function (s) {
    if(xss_vector_map()[s]){
     console.log('XSS caught')
    }
  });
}


      $(scanloader).hide();
      $(submit).on('click',function(e){


        var scan_url = $('#scan-url');
        var scan_url_value = $('#scan-url').val();
        var xss_filter = '';


        $('.details_content').each(function(index){
               $(this).remove();
         });
        $('.value').each(function(index){
               $(this).remove();
         });

            
          e.preventDefault();

         


          if(scan_url_value){



                /***If Scan url is a valid url****/
               if(isUrl(scan_url_value)){

                $(scandetails).hide();
                $(scanform).hide();
                $(scanloader).show();
              // $(imageloader).show();


              if(escapeHtml(scan_url_value) !== "XSS Caught"){
                        
               $.ajaxSetup({

                     headers:{'X-CSRF-Token':$('meta[name=_token]').attr('content')}
               });  


               jQuery.ajax({
                   url:'http://50.63.161.157/scanapp/index.php',
                   type:'GET',
                   data:{
                       scanurl:scan_url_value
                   },
                   success:function(data){
                       
                       //var json_data = JSON.parse(data);
                       
                          jQuery.ajax({
                              url:"http://50.63.161.157/scanapp/scan_results.php",
                              type:"GET",
                              success:function(response){

                                   $.each(response,function(key,value){
                                         
                                           /****If our object is not empty****/ 
                                         if(Object.keys(value).length !== 0){
                                             console.log(value);
                                              
                                             document.getElementById('url').innerHTML = value.target;

                                             js_traverse(value); 




                                         }
                                   });

                              },
                              error:function(response){
                                   console.log(response);
                              }
                          });
                      
                       
                      

 
                        $(scandetails).show();
                        $(scanform).show();

                        $(scanloader).hide();

                   },
                   error:function(xhr,error,c){
                      console.log(error);
                      if(error){
                         $("#scan-h3").html("Invalid URL, Please try again.");
                         setTimeout(function(){
                             $("#scan-h3").html("Scanning");
                            $(scandetails).show();
                             $(scanform).show();
                             $(scanloader).hide();
                         },3000);
                        
                      }

                   }
               }); 

              }else{
                alert("XSS Caught");
              }
                         
                       
               }else{
                 $('#scan-url').val("Please enter a valid URL");


               }
               
          }else{
              $('#scan-url').prop('placeholder','Please Enter a Valid URL');
          }

             
      });


});
</script>
<div class="container">
    <!-- <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    You are logged in!
                </div>

                  
            </div>
        </div>
    </div> -->
    <div class="row scan-content-container">
          <div class="col-md-12 ">
              <h2>Web URL Security Scan</h2>
              <h3>Online Security Scanner to test vulnerabilities of a url. Checks include application security, WordPress plugins, hosting environment and web server.
              </h3>
          </div>

    </div>

    <div class="row scan-content-input-container">

       <div class="col-md-12 scan-loader" id="scan-loader">
            <h3 id="scan-h3" class="scan-h3">Scanning.....</h3>
<!--             <img id="image-loader" src="{{url('/img/loader.gif')}}" alt="">
 -->       </div>
          <form class="scan-form" id="scan-form" name="form" method="post" action="#" enctype="form-data">
           {{ csrf_field() }}
              <div class="col-md-4 ">
                      
                  <input class="form-control" type="text" name="scan-url" id="scan-url" placeholder="URL to Scan"/>
              </div>
              <br/>
              <br/>
             <div class="col-md-4 ">

                 <button type="button" id="submit" class="btn btn-danger">Run URL Scanner</button>
            </div> 

          </form>
    </div>

    <div class="row scan-details" id="scan-details">
         <h3>Analysis of <span id="url"></span></h3>

         <table class="table table-bordered table-condensed">
             <tr>
               <td rowspan="5" style="width:130px" rowspan="5" style="width:130px">
                 <img id="logo" src="{{url('/img/scanner.png')}}"/>
               </td>
              
              <!--  <td rowspan="5">Google safe browse check</td> -->
             </tr>
             <tr>
                <td> 
                <div id="details"></div>
                </td>
                <!-- <td> 
                Country:
                <h3 id="country"></h3>
                </td> -->
             </tr>
             <!-- <tr id="jquery-container" style="display: none">
                <td>Jquery
                 <h3 id="jquery">N/A</h3>
                </td>
             </tr>  -->
             <!-- <tr>
                <td>
                IP Address:
                <h3 id="ip"></h3>
                </td>
             </tr> -->
             <!-- <tr>
                <td>Provider: 
               <h3 id="provider">N/A</h3>
                </td>
             </tr> -->
         </table>
    </div>
</div>
@endsection
