// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
function searchFun() {
    let filter = document.getElementById('myinput').value.toUpperCase();
    let mytable = document.getElementById('mytable');
    let tr = mytable.getElementsByTagName('tr');

    for (var i = 0; i < tr.length; i++) {
        let td = tr[i].getElementsByTagName('td')[0];
        if (td) {
            let textvalue = td.textContent || td.innerText; // Use innerText instead of innerHTML for text content
            if (textvalue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = 'none';
            }
        }
    }
}