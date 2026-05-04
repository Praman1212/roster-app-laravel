import { useCallback, useEffect, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { format, isToday, isTomorrow, parseISO } from 'date-fns'
import api from '../api'
import TaskModal  from '../components/TaskModal'
import NotifPanel from '../components/NotifPanel'

const CAT_ICON = {
  general:'📋', cooking:'🍳', cleaning:'🧹',
  shopping:'🛒', work:'💼', health:'💊', school:'🎒'
}

export default function HomePage({ user, setUser }) {
  const nav = useNavigate()
  const [tasks,         setTasks]         = useState([])
  const [notifications, setNotifications] = useState([])
  const [filter,        setFilter]        = useState('all')
  const [showModal,     setShowModal]     = useState(false)
  const [editTask,      setEditTask]      = useState(null)
  const [showNotif,     setShowNotif]     = useState(false)

  const loadTasks = useCallback(async () => {
    const res = await api.get('/tasks')
    setTasks(res.data)
  }, [])

  const loadNotifs = useCallback(async () => {
    const res = await api.get('/notifications')
    setNotifications(res.data)
  }, [])

  useEffect(() => {
    loadTasks()
    loadNotifs()
  }, [loadTasks, loadNotifs])

  async function saveTask(formData) {
    if (editTask) {
      const res = await api.put(`/tasks/${editTask.id}`, formData)
      setTasks(prev => prev.map(t => t.id === res.data.id ? res.data : t))
    } else {
      const res = await api.post('/tasks', formData)
      setTasks(prev => [...prev, res.data])
    }
    setShowModal(false)
    setEditTask(null)
    loadNotifs()
  }

  async function deleteTask(id) {
    if (!confirm('Delete this task?')) return
    await api.delete(`/tasks/${id}`)
    setTasks(prev => prev.filter(t => t.id !== id))
    loadNotifs()
  }

  async function changeStatus(task, status) {
    const res = await api.put(`/tasks/${task.id}`, { status })
    setTasks(prev => prev.map(t => t.id === res.data.id ? res.data : t))
  }

  async function logout() {
    await api.post('/logout')
    localStorage.removeItem('token')
    setUser(null)
    nav('/login')
  }

  const todayStr    = new Date().toISOString().slice(0, 10)
  const members     = user.household?.members || []
  const unreadCount = notifications.filter(n => !n.read).length

  const filtered = tasks.filter(t => {
    if (filter === 'mine')     return t.user_id === user.id
    if (filter === 'today')    return t.start_date === todayStr
    if (filter === 'upcoming') return t.start_date > todayStr
    return true
  })

  const grouped = filtered.reduce((acc, t) => {
    if (!acc[t.start_date]) acc[t.start_date] = []
    acc[t.start_date].push(t)
    return acc
  }, {})

  function dateLabel(str) {
    const d = parseISO(str)
    if (isToday(d))    return '🟢 Today'
    if (isTomorrow(d)) return '🟡 Tomorrow'
    return format(d, 'EEE, d MMM yyyy')
  }

  return (
    <div className="app">
      {/* TOP BAR */}
      <header className="topbar">
        <div className="topbar-left">
          <span className="logo">🏠 FamRoster</span>
          {user.household && <span className="house-name">{user.household.name}</span>}
        </div>
        <div className="topbar-right">
          <div className="member-dots">
            {members.map(m => (
              <div key={m.id} className="mdot" style={{ background: m.color }} title={m.name}>
                {m.name[0].toUpperCase()}
              </div>
            ))}
          </div>
          <button className="bell-btn" onClick={() => setShowNotif(v => !v)}>
            🔔 {unreadCount > 0 && <span className="bell-count">{unreadCount}</span>}
          </button>
          <button className="signout-btn" onClick={logout}>Sign out</button>
        </div>
      </header>

      {/* TOOLBAR */}
      <div className="toolbar">
        {['all','mine','today','upcoming'].map(f => (
          <button
            key={f}
            className={`filter-btn ${filter === f ? 'active' : ''}`}
            onClick={() => setFilter(f)}
          >
            {f.charAt(0).toUpperCase() + f.slice(1)}
          </button>
        ))}
        <div className="invite-info">
          Invite code: <strong>{user.household?.invite_code}</strong>
        </div>
        <button className="add-task-btn" onClick={() => { setEditTask(null); setShowModal(true) }}>
          + Add Task
        </button>
      </div>

      {/* TASK LIST */}
      <main className="main">
        {Object.keys(grouped).length === 0 && (
          <div className="empty-state">
            <div>📅</div>
            <p>No tasks here. Add one!</p>
          </div>
        )}
        {Object.keys(grouped).sort().map(date => (
          <div key={date}>
            <div className="date-group-label">{dateLabel(date)}</div>
            {grouped[date].map(task => {
              const assignee = members.find(m => m.id === task.user_id)
              return (
                <div key={task.id} className={`task-card ${task.status}`}>
                  <span className="task-cat-icon">{CAT_ICON[task.category] || '📋'}</span>
                  <div className="task-body">
                    <div className="task-title">{task.title}</div>
                    {task.description && <div className="task-desc">{task.description}</div>}
                    <div className="task-tags">
                      {task.start_time && <span className="tag tag-blue">🕐 {task.start_time.slice(0,5)}</span>}
                      <span className="tag tag-purple">{task.recurrence}</span>
                      {assignee && (
                        <span className="tag" style={{ color: assignee.color, background: assignee.color + '15' }}>
                          ● {assignee.name}
                        </span>
                      )}
                    </div>
                  </div>
                  <div className="task-right">
                    <select className="status-sel" value={task.status} onChange={e => changeStatus(task, e.target.value)}>
                      <option value="pending">⏳ Pending</option>
                      <option value="in_progress">🔵 In Progress</option>
                      <option value="done">✅ Done</option>
                    </select>
                    <button className="icon-btn" onClick={() => { setEditTask(task); setShowModal(true) }}>✏️</button>
                    <button className="icon-btn" onClick={() => deleteTask(task.id)}>🗑️</button>
                  </div>
                </div>
              )
            })}
          </div>
        ))}
      </main>

      {showModal && (
        <TaskModal
          task={editTask}
          members={members}
          currentUser={user}
          onSave={saveTask}
          onClose={() => { setShowModal(false); setEditTask(null) }}
        />
      )}

      {showNotif && (
        <NotifPanel
          notifications={notifications}
          onMarkRead={async id => { await api.patch(`/notifications/${id}/read`); loadNotifs() }}
          onMarkAll={async () => { await api.post('/notifications/read-all'); loadNotifs() }}
          onClose={() => setShowNotif(false)}
        />
      )}
    </div>
  )
}