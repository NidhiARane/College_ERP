const API = "API/students.php";

async function request(method, data)
{
    let url = API;

    if (method === "GET" && data?.id) {
        url += "?id=" + data.id;
    }

    const response = await fetch(url, {
        method,
        headers: {"Content-Type": "application/json"},
        body: method === "GET" ? null : JSON.stringify(data)
    });

    return await response.json();
}

async function loadstudents()
{
    const table = document.getElementById("studentTableBody");
    const students = await request("GET");
    const search = document.getElementById("search").value.toLowerCase();

    table.innerHTML = "";

    students.filter(s =>
        [s.name, s.email, s.usn, s.course, s.semester]
        .some(v => String(v).toLowerCase().includes(search))
    ).forEach(s => {
        const row = document.createElement("tr");

        [s.id, s.name, s.email, s.usn, s.course, s.semester, s.phone || "N/A"]
        .forEach(v => {
            const td = document.createElement("td");
            td.textContent = v;
            row.appendChild(td);
        });

        const td = document.createElement("td");
        td.innerHTML = `<button class="btn btn-warning btn-sm" onclick="editstudent(${s.id})">Edit</button>
        <button class="btn btn-danger btn-sm" onclick="deletestudent(${s.id})">Delete</button>`;

        row.appendChild(td);
        table.appendChild(row);
    });

    if (!table.children.length)
        table.innerHTML = '<tr><td colspan="8" class="text-center">No students found</td></tr>';
}

async function deletestudent(id)
{
    if(confirm("Are you sure you want to delete this student?"))
    {
        await request("DELETE", {id});
        loadstudents();
    }
}

async function editstudent(id)
{
    
        const student = await request("GET", {id});
        const name = prompt("Enter new name:", student.name);
        const email = prompt("Enter new email:", student.email);
        const usn = prompt("Enter new usn:", student.usn);
        const course = prompt("Enter new course:", student.course);
        const semester = prompt("Enter new semester:", student.semester);
        const phone = prompt("Enter new phone:", student.phone);
        await request("PUT", {id, name, email, usn, course, semester, phone});
        loadstudents();
}

async function addstudent()
{
    const name = prompt("Enter name:");
    const email = prompt("Enter email:");
    const usn = prompt("Enter usn:");
    const course = prompt("Enter course:");
    const semester = prompt("Enter semester:");
    const phone = prompt("Enter phone:");
    if(!name || !email || !usn || !course || !semester || !phone)
    {
        alert("Please fill in all fields.");
        return;
    }
    await request("POST", {name, email, usn, course, semester, phone});
    loadstudents();
}

    



