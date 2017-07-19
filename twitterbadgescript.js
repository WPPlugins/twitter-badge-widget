//Format the JSON returned by the Twitter API 

function TBW_twitterformat(twitters, maxtwits) {
  var statusHTML = [];
  
  if (twitters.error === undefined)
  {
  for (var i=0; i<twitters.length & i<maxtwits; i++){
    var username = twitters[i].user.screen_name;
    var status = twitters[i].text.replace(/((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;'">\:\s\<\>\)\]\!])/g, function(url) {
      return '<a href="'+url+'">'+url+'</a>';
    }).replace(/\B@([_a-z0-9]+)/ig, function(reply) {
      return  reply.charAt(0)+'<a href="http://twitter.com/'+reply.substring(1)+'">'+reply.substring(1)+'</a>';
    }).replace(/[#]+[A-Za-z0-9-_]+/g, function(t) {
		var tag = t.replace("#","%23");
		return t.link("http://search.twitter.com/search?q="+tag);
	});
    statusHTML.push('<li><span class="TBW_Tweets">'+status+'</span> <span class="TBW_Time"><a href="http://twitter.com/'+username+'/statuses/'+twitters[i].id+'"><abbr class="datetime" title="'+twitters[i].created_at+'">'+Loc_relative_time(twitters[i].created_at)+'</abbr></span></a></li>');
  }
  return (statusHTML.join(''));
  }
  else {return twitters.error;}
}

function TBW_ProcessWidgets(jTB)
{
    jTB(".TBW_Data" ).each( function( intIndex,oElement ){
           var oE = jTB("#"+oElement.id);
           oE.html("<span class=TBW_Error>Timeout or "+oE.attr("data-TBWtwitterid")+" is invalid</span>");
            //Caching using jStorage   
            var oHTML = jTB.jStorage.get("TBW_HTML");
            if(!oHTML || oHTML.search("TBW_Error") != -1){
                    if (TBWTimeout === undefined) {var TBWTimeout = 60000;} // expires in 1 minute
                    jTB.ajaxSetup ({ cache: false});
                    jTB.getJSON(oE.attr("data-TBWjsonurl"),'',function(data) {
                        oHTML = TBW_twitterformat(data,oE.attr("data-TBWcount"));
                        jTB.jStorage.set(oElement.id, oHTML);
                        jTB.jStorage.setTTL(oElement.id, TBWTimeout); 
                        oE.html(oHTML);
                    });
            }
            else
            {
                oE.html(oHTML); //Display cached version
            }
        });        
}

//Now load the widgets script for the follow button and call out to our process function
(function(){
        try {var jTB = jQuery.noConflict();}
        catch(e) {console.log(e);
                  throw new Error("jQuery should be loaded before Twitter Badge Widget!");}
        
        jTB(function() {
        try {TBW_ProcessWidgets(jTB);}
        catch(e) {console.log('Failed to display tweets');}
        }); //Run on DOM ready
        
    //http://dev.twitter.com/pages/follow_button
    var twitterWidgets = document.createElement('script');
    twitterWidgets.type = 'text/javascript';
    twitterWidgets.async = true;
    twitterWidgets.src = 'http://platform.twitter.com/widgets.js';
    document.getElementsByTagName('head')[0].appendChild(twitterWidgets);
  })();//Run on DOM ready

