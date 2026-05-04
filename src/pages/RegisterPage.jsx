import { useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import api from '../api'

const COLORS = ['#6366f1','#f43f5e','#22c55e','#f59e0b','#06b6d4','#a855f7']

export default function RegisterPage({ setUser }) {
  const nav = useNavigate()
  const [name,     setName]     = useState('')
  const [email,    setEmail]    = useState('')
  const [password, setPassword] = useState('')
  const [confirm,  setConfirm]  = useState('')
  const [color,    setColor]    = useState('#6366f1')
  const [error,    setError]    = useState('')

  async function handleSubmit(e) {
    e.preventDefault()
    setError('')
    try {
      const res = await api.post('/register', {
        name, email, password,
        password_confirmation: confirm,
        color
      })
      localStorage.setItem('token', res.data.token)
      setUser(res.data.user)
      nav('/setup')
    } catch (err) {
      setError(err.response?.data?.message || 'Registration failed')
    }
  }

  return (
    <div className="auth-page">
      <div className="auth-box">
        <h1>🏠 FamRoster</h1>
        <p>Create your account</p>
        {error && <div className="error-msg">{error}</div>}
        <form onSubmit={handleSubmit}>
          <label>Your Name</label>
          <input value={name} onChange={e => setName(e.target.value)} required />
          <label>Email</label>
          <input type="email" value={email} onChange={e => setEmail(e.target.value)} required />
          <label>Password</label>
          <input type="password" value={password} onChange={e => setPassword(e.target.value)} required />
          <label>Confirm Password</label>
          <input type="password" value={confirm} onChange={e => setConfirm(e.target.value)} required />
          <label>Pick Your Color</label>
          <div className="color-row">
            {COLORS.map(c => (
              <div
                key={c}
                className={`color-circle ${color === c ? 'active' : ''}`}
                style={{ background: c }}
                onClick={() => setColor(c)}
              />
            ))}
          </div>
          <button type="submit" className="btn-main">Create Account</button>
        </form>
        <p className="switch">
          Have an account? <Link to="/login">Login</Link>
        </p>
      </div>
    </div>
  )
}