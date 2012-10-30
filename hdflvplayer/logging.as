//trackGA (categoryOrPageTrack [required], action [required], label [optional], value [optional]
//categoryOrPageTrack - either the category string or a string saying 'page'
function trackGA(categoryOrPageTrack:String, action:String, optional_label:String, optional_value:Number) {
	//call page tracking version of Google analytics
	if (categoryOrPageTrack == "page") {
		//trace("GATC pageTracker call");
		trackGAPage(action);
	}
	//call event tracking method
	else {
		//trace("GATC event tracker call");
		trackGAEvent(categoryOrPageTrack, action, optional_label, optional_value);
	}
}

var prefix:String = "flashGA";
//Google Analytics Calls Page Tracking - for tracking page views
function trackGAPage(action:String) {
	//GA call
	if (prefix != null && !eventTrack){
		var call = "/" + prefix + "/" + action;
		//Old Google Analytics Code (urchinTracker)
		ExternalInterface.call("urchinTracker('"+call+"')");
		//New Google Analytics Code (_trackPageview) pageview
		ExternalInterface.call("pageTracker._trackPageview('"+call+"')");
		trace("==GATC==pageTracker._trackPageview('"+call+"')");
	}
	_root.tracer.text = action;
}


//Google Analytics Event Tracking Calls - for tracking events and not pageviews
//category, action, label (optional), value(optional)
function trackGAEvent(category:String, action:String,  optional_label:String, optional_value:Number) {
	/*
	objectTracker_trackEvent(category, action, optional_label, optional_value)
	category (required) - The name you supply for the group of objects you want to track.
	action (required) - A string that is uniquely paired with each category, and commonly used to define the type of user interaction for the web object.
	label (optional) - An optional string to provide additional dimensions to the event data.
	value (optional) - An optional integer that you can use to provide numerical data about the user event.
	*/

	theCategory = "'" + category;
	theAction = "', '" + action + "'";
	theLabel = (optional_label == null) ? "" : ", '" + optional_label + "'";
	theValue = (optional_value == null) ? "" : ", " + optional_value;
	//New Google Analytics Code (_trackEvent) event tracking
	theCall = "pageTracker._trackEvent(" + theCategory + theAction + theLabel + theValue + ")";
	ExternalInterface.call(theCall);
	trace("====GATC===="+theCall);
	_root.tracer.text = theCategory + theAction + theLabel + theValue;
}