import { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import api from '../api'

export default function SetupPage({ setUser }) {
  const nav = useNavigate()
  const [tab,   setTab]   = useState('create')
  const [name,  setName]  = useState('')
  const [code,  setCode]  = useState('')
  const [error, setError] = useState('')

  async function handleCreate(e) {
    e.preventDefault()
    setError('')
    try {
      await api.post('/household/create', { name })
      const res = await api.get('/me')
      setUser(res.data)
      nav('/')
    } catch {
      setError('Could not create household')
    }
  }

  async function handleJoin(e) {
    e.preventDefault()
    setError('')
    try {
      await api.post('/household/join', { invite_code: code })
      const res = await api.get('/me')
      setUser(res.data)
      nav('/')
    } catch {
      setError('Invite code not found!')
    }
  }

  return (
    <div className="auth-page">
      <div className="auth-box" style={{ maxWidth: 440 }}>
        <h1>🏡 Setup</h1>
        <p>Create a household or join your partner's</p>
        {error && <div className="error-msg">{error}</div>}
        <div className="tabs">
          <button className={tab === 'create' ? 'active' : ''} onClick={() => setTab('create')}>Create New</button>
          <button className={tab === 'join'   ? 'active' : ''} onClick={() => setTab('join')}>Join Existing</button>
        </div>
        {tab === 'create' ? (
          <form onSubmit={handleCreate}>
            <label>Household Name</label>
            <input placeholder="e.g. The Smiths" value={name} onChange={e => setName(e.target.value)} required />
            <button type="submit" className="btn-main">Create Household</button>
          </form>
        ) : (
          <form onSubmit={handleJoin}>
            <label>Invite Code</label>
            <input
              placeholder="e.g. ABCD1234"
              value={code}
              onChange={e => setCode(e.target.value.toUpperCase())}
              required
            />
            <button type="submit" className="btn-main">Join Household</button>
          </form>
        )}
      </div>
    </div>
  )
}