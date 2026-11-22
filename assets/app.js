
const API = 'api/students.php';

async function loadStudents() {
    const body = document.getElementById('studentTableBody');
    const search = document.getElementById('search').value.toLowerCase();

    try {
        const res = await fetch(API);
        if (!res.ok) throw Error('API error: ' + res.status);

        const students = await res.json();
        body.innerHTML = '';

        students.filter(s =>
            [s.name, s.email, s.usn, s.course, s.semester]
            .some(v => String(v).toLowerCase().includes(search))
        ).forEach(s => {
            const row = document.createElement('tr');

            [s.id, s.name, s.email, s.usn, s.course,
             s.semester, s.phone || '-'].forEach(v => {
                const td = document.createElement('td');
                td.textContent = v;
                row.appendChild(td);
            });

            const actions = document.createElement('td');

            const edit = document.createElement('button');
            edit.textContent = 'Edit';
            edit.className = 'btn btn-warning btn-sm me-1';
            edit.onclick = () => editStudent(s);

            const del = document.createElement('button');
            del.textContent = 'Delete';
            del.className = 'btn btn-danger btn-sm';
            del.onclick = () => deleteStudent(s.user_id);

            actions.append(edit, del);
            row.appendChild(actions);
            body.appendChild(row);
        });

        if (!body.children.length)
            body.innerHTML = '<tr><td colspan="8" class="text-center">No records found</td></tr>';

    } catch (e) {
        body.innerHTML = `<tr><td colspan="8" class="text-danger">${e.message}</td></tr>`;
        console.error(e);
    }
}

async function editStudent(s) {
    const course = prompt('Course:', s.course);
    if (course === null) return;

    const semester = prompt('Semester:', s.semester);
    if (semester === null) return;

    const phone = prompt('Phone:', s.phone || '');
    if (phone === null) return;

    await fetch(API, {
        method: 'PUT',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
                user_id: s.user_id,
                usn:s.usn,
                course,
                semester,
                phone
            })
    });

    loadStudents();
}

async function deleteStudent(user_id) {
    if (!confirm('Delete this student?')) return;

    await fetch(API, {
        method: 'DELETE',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({user_id: user_id})
    });

    loadStudents();
}

// Search button and Enter key
document.getElementById('searchBtn').onclick = loadStudents;

document.getElementById('search').onkeyup = e => {
    if (e.key === 'Enter') loadStudents();
};

// Load records when page opens
loadStudents();
