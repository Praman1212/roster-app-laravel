import { useEffect, useState } from 'react'
import { BrowserRouter, Navigate, Route, Routes } from 'react-router-dom'
import api from './api'
import LoginPage    from './pages/LoginPage'
import RegisterPage from './pages/RegisterPage'
import SetupPage    from './pages/SetupPage'
import HomePage     from './pages/HomePage'

export default function App() {
  const [user,    setUser]    = useState(null)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    const token = localStorage.getItem('token')
    if (token) {
      api.get('/me')
        .then(r => setUser(r.data))
        .catch(() => localStorage.removeItem('token'))
        .finally(() => setLoading(false))
    } else {
      setLoading(false)
    }
  }, [])

  if (loading) return <div className="loading">Loading...</div>

  return (
    <BrowserRouter>
      <Routes>
        <Route path="/login"    element={<LoginPage    setUser={setUser} />} />
        <Route path="/register" element={<RegisterPage setUser={setUser} />} />
        <Route path="/setup" element={
          user ? <SetupPage user={user} setUser={setUser} />
               : <Navigate to="/login" />
        }/>
        <Route path="/" element={
          !user              ? <Navigate to="/login" />  :
          !user.household_id ? <Navigate to="/setup" />  :
          <HomePage user={user} setUser={setUser} />
        }/>
      </Routes>
    </BrowserRouter>
  )
}