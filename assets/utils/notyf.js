import {Notyf} from "notyf";
import 'notyf/notyf.min.css';

const notyf = new Notyf({
    duration: 5000, position: {
        x: 'right', y: 'bottom',
    }, types: [{
        type: 'warning', background: 'orange', icon: {
            className: 'material-icons', tagName: 'i', text: 'warning'
        }
    }, {
        type: 'error', background: 'indianred', dismissible: true
    }, {
        type: 'success', background: 'green', dismissible: true
    }]
});

export default function (type, message) {
    notyf.open({
        type, message
    });
}
