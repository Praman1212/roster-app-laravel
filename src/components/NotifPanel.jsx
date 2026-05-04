import { formatDistanceToNow, parseISO } from 'date-fns'

const TYPE_ICON = {
  created: '✅',
  updated: '✏️',
  deleted: '🗑️',
  reminder: '⏰'
}

export default function NotifPanel({ notifications, onMarkRead, onMarkAll, onClose }) {
  return (
    <div className="notif-panel">

      <div className="notif-panel-header">
        <strong>🔔 Notifications</strong>
        <div>
          <button className="btn-tiny" onClick={onMarkAll}>Mark all read</button>
          <button className="modal-close" onClick={onClose}>✕</button>
        </div>
      </div>

      <div className="notif-scroll">
        {notifications.length === 0 && (
          <p className="notif-empty">All caught up! 🎉</p>
        )}

        {notifications.map(n => (
          <div
            key={n.id}
            className={`notif-row ${n.read ? '' : 'unread'}`}
            onClick={() => !n.read && onMarkRead(n.id)}
          >
            <span style={{ fontSize: 16 }}>{TYPE_ICON[n.type] || '🔔'}</span>
            <div>
              <div>{n.message}</div>
              <div className="notif-time">
                {formatDistanceToNow(parseISO(n.created_at), { addSuffix: true })}
              </div>
            </div>
          </div>
        ))}
      </div>

    </div>
  )
}