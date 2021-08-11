import Tooltip from "@/libs/Tooltip";
import '@/libs/_tooltip.scss';

let elements = document.querySelectorAll(".cs-tooltip");
if (elements.length) {
	elements.forEach(element => new Tooltip(element));
}
