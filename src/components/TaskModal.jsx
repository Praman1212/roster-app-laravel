import { useState } from 'react'

const CATEGORIES  = ['general','cooking','cleaning','shopping','work','health','school']
const RECURRENCES = ['once','weekly','fortnightly','monthly']

export default function TaskModal({ task, members, currentUser, onSave, onClose }) {

  // pre-fill if editing, blank if new
  const [form, setForm] = useState({
    title:       task?.title                    || '',
    description: task?.description              || '',
    user_id:     task?.user_id                  || currentUser.id,
    category:    task?.category                 || 'general',
    recurrence:  task?.recurrence               || 'once',
    start_date:  task?.start_date?.slice(0,10)  || new Date().toISOString().slice(0,10),
    start_time:  task?.start_time?.slice(0,5)   || '',
    status:      task?.status                   || 'pending',
  })

  // update one field at a time
  const set = (field, value) => setForm(prev => ({ ...prev, [field]: value }))

  async function handleSubmit(e) {
    e.preventDefault()
    await onSave(form)
  }

  return (
    <div className="modal-bg" onClick={e => e.target.className === 'modal-bg' && onClose()}>
      <div className="modal-box">

        <div className="modal-header">
          <h2>{task ? '✏️ Edit Task' : '✨ New Task'}</h2>
          <button className="modal-close" onClick={onClose}>✕</button>
        </div>

        <form onSubmit={handleSubmit} className="modal-body">

          <label>Title *</label>
          <input
            value={form.title}
            onChange={e => set('title', e.target.value)}
            placeholder="e.g. Buy groceries"
            required
          />

          <label>Description (optional)</label>
          <textarea
            rows={2}
            value={form.description}
            onChange={e => set('description', e.target.value)}
            placeholder="Any notes..."
          />

          <div className="two-col">
            <div>
              <label>Assign To</label>
              <select
                value={form.user_id}
                onChange={e => set('user_id', Number(e.target.value))}
              >
                {members.map(m => (
                  <option key={m.id} value={m.id}>{m.name}</option>
                ))}
              </select>
            </div>
            <div>
              <label>Category</label>
              <select
                value={form.category}
                onChange={e => set('category', e.target.value)}
              >
                {CATEGORIES.map(c => (
                  <option key={c} value={c}>{c}</option>
                ))}
              </select>
            </div>
          </div>

          <div className="two-col">
            <div>
              <label>Date *</label>
              <input
                type="date"
                value={form.start_date}
                onChange={e => set('start_date', e.target.value)}
                required
              />
            </div>
            <div>
              <label>Time (optional)</label>
              <input
                type="time"
                value={form.start_time}
                onChange={e => set('start_time', e.target.value)}
              />
            </div>
          </div>

          <div className="two-col">
            <div>
              <label>Repeats</label>
              <select
                value={form.recurrence}
                onChange={e => set('recurrence', e.target.value)}
              >
                {RECURRENCES.map(r => (
                  <option key={r} value={r}>{r}</option>
                ))}
              </select>
            </div>
            <div>
              <label>Status</label>
              <select
                value={form.status}
                onChange={e => set('status', e.target.value)}
              >
                <option value="pending">Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="done">Done</option>
              </select>
            </div>
          </div>

          <div className="modal-footer">
            <button type="button" className="btn-cancel" onClick={onClose}>
              Cancel
            </button>
            <button type="submit" className="btn-save">
              💾 Save Task
            </button>
          </div>

        </form>
      </div>
    </div>
  )
}