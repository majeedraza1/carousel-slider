import $ from "jquery";
import EventBus from "@/admin/EventBus";

let section_video_settings = $('#section_video_settings');

EventBus.onChangeSlideType(_type => {
	if ('video-carousel' === _type) {
		section_video_settings.slideDown();
	} else {
		section_video_settings.hide('fast');
	}
})
