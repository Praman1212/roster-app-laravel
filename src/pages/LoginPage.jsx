import { useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import api from '../api'

export default function LoginPage({ setUser }) {
  const nav = useNavigate()
  const [email,    setEmail]    = useState('')
  const [password, setPassword] = useState('')
  const [error,    setError]    = useState('')

  async function handleSubmit(e) {
    e.preventDefault()
    setError('')
    try {
      const res = await api.post('/login', { email, password })
      localStorage.setItem('token', res.data.token)
      setUser(res.data.user)
      nav('/')
    } catch {
      setError('Wrong email or password!')
    }
  }

  return (
    <div className="auth-page">
      <div className="auth-box">
        <h1>🏠 FamRoster</h1>
        <p>Your shared family schedule</p>
        {error && <div className="error-msg">{error}</div>}
        <form onSubmit={handleSubmit}>
          <label>Email</label>
          <input type="email" value={email} onChange={e => setEmail(e.target.value)} required />
          <label>Password</label>
          <input type="password" value={password} onChange={e => setPassword(e.target.value)} required />
          <button type="submit" className="btn-main">Sign In</button>
        </form>
        <p className="switch">
          No account? <Link to="/register">Register here</Link>
        </p>
      </div>
    </div>
  )
}