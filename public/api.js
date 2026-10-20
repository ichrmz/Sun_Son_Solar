// Drop this file in /public and add <script src="api.js"></script> BEFORE script.js
// Every call returns { ok, status, data }.
//   Success:  data.message (+ data.user on login)
//   Fail 400: data.messages = { first_name: "...", email: "...", ... }  -> show under each field
//   Fail 401/403: data.messages.error = "Invalid username or password."
async function sunApi(path, body, method = 'POST') {
  const res = await fetch('/api/' + path, {
    method,
    headers: { 'Content-Type': 'application/json' },
    body: method === 'GET' ? undefined : JSON.stringify(body || {}),
  });
  const data = await res.json().catch(() => ({}));
  return { ok: res.ok, status: res.status, data };
}

// form = { role: 'customer' | 'employee', first_name, middle_name, last_name, birthdate (YYYY-MM-DD),
//          gender, email, phone, address, username, password, confirm_password, department (employee only) }
const registerUser = (form) => sunApi('register', form);
const loginUser    = (username, password) => sunApi('login', { username, password });
const logoutUser   = () => sunApi('logout');
const currentUser  = () => sunApi('me', null, 'GET');
