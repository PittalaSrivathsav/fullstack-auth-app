import React, { useState, useEffect } from "react";
import "./App.css";

function App() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [loggedIn, setLoggedIn] = useState(false);
  const [users, setUsers] = useState([]);
  const [teachers, setTeachers] = useState([]);

  const handleLogin = async () => {
    try {
      const response = await fetch("http://localhost:8080/login", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({ email, password })
      });

      const data = await response.json();

      if (data.token) {
        localStorage.setItem("token", data.token);
        setLoggedIn(true);
      } else {
        alert(data.error);
      }
    } catch (error) {
      alert("Server error");
    }
  };

  useEffect(() => {
    if (loggedIn) {
      const token = localStorage.getItem("token");

      fetch("http://localhost:8080/users", {
        headers: {
          Authorization: "Bearer " + token
        }
      })
        .then(res => res.json())
        .then(data => setUsers(data));

      fetch("http://localhost:8080/teachers", {
        headers: {
          Authorization: "Bearer " + token
        }
      })
        .then(res => res.json())
        .then(data => setTeachers(data));
    }
  }, [loggedIn]);

  // ✅ DASHBOARD
  if (loggedIn) {
    return (
      <div className="container">
        <h1>Dashboard</h1>

        <div className="table-container">
          <h2>Users</h2>
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Email</th>
                <th>First Name</th>
                <th>Last Name</th>
              </tr>
            </thead>
            <tbody>
              {users.map((u) => (
                <tr key={u.id}>
                  <td>{u.id}</td>
                  <td>{u.email}</td>
                  <td>{u.first_name}</td>
                  <td>{u.last_name}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>

        <div className="table-container">
          <h2>Teachers</h2>
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>University</th>
                <th>Gender</th>
                <th>Year Joined</th>
              </tr>
            </thead>
            <tbody>
              {teachers.map((t) => (
                <tr key={t.id}>
                  <td>{t.id}</td>
                  <td>{t.user_id}</td>
                  <td>{t.university_name}</td>
                  <td>{t.gender}</td>
                  <td>{t.year_joined}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    );
  }

  // ✅ LOGIN UI
  return (
    <div className="container" style={{ textAlign: "center" }}>
      <h1>Login</h1>

      <input
        type="email"
        placeholder="Enter Email"
        onChange={(e) => setEmail(e.target.value)}
      />
      <br />

      <input
        type="password"
        placeholder="Enter Password"
        onChange={(e) => setPassword(e.target.value)}
      />
      <br />

      <button onClick={handleLogin}>Login</button>
    </div>
  );
}

export default App;