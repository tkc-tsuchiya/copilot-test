import { useState, useEffect } from 'react'
import './App.css'

interface Message {
  id: number;
  name: string;
  message: string;
  timestamp: string;
}

function App() {
  const [messages, setMessages] = useState<Message[]>([])
  const [name, setName] = useState('')
  const [newMessage, setNewMessage] = useState('')
  const [isLoading, setIsLoading] = useState(false)

  const API_BASE = window.location.hostname === 'localhost' 
    ? 'http://localhost:8000' 
    : '/backend'

  // Fetch messages from the API
  const fetchMessages = async () => {
    try {
      const response = await fetch(`${API_BASE}/api/messages`)
      if (response.ok) {
        const data = await response.json()
        setMessages(data)
      }
    } catch (error) {
      console.error('Error fetching messages:', error)
    }
  }

  // Send a new message
  const sendMessage = async (e: React.FormEvent) => {
    e.preventDefault()
    if (!name.trim() || !newMessage.trim()) return

    setIsLoading(true)
    try {
      const response = await fetch(`${API_BASE}/api/messages`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          name: name.trim(),
          message: newMessage.trim(),
        }),
      })

      if (response.ok) {
        setNewMessage('')
        fetchMessages() // Refresh messages
      }
    } catch (error) {
      console.error('Error sending message:', error)
    } finally {
      setIsLoading(false)
    }
  }

  // Load messages on component mount and set up polling
  useEffect(() => {
    fetchMessages()
    const interval = setInterval(fetchMessages, 2000) // Poll every 2 seconds
    return () => clearInterval(interval)
  }, [])

  return (
    <div className="chat-app">
      <header className="chat-header">
        <h1>🗨️ チャットアプリ</h1>
      </header>

      <main className="chat-main">
        <div className="messages-container">
          {messages.length === 0 ? (
            <p className="no-messages">まだメッセージがありません</p>
          ) : (
            messages.map((msg) => (
              <div key={msg.id} className="message">
                <div className="message-header">
                  <span className="message-name">{msg.name}</span>
                  <span className="message-time">{msg.timestamp}</span>
                </div>
                <div className="message-content">{msg.message}</div>
              </div>
            ))
          )}
        </div>

        <form onSubmit={sendMessage} className="message-form">
          <div className="form-row">
            <input
              type="text"
              placeholder="お名前"
              value={name}
              onChange={(e) => setName(e.target.value)}
              className="name-input"
              required
            />
          </div>
          <div className="form-row">
            <input
              type="text"
              placeholder="メッセージを入力..."
              value={newMessage}
              onChange={(e) => setNewMessage(e.target.value)}
              className="message-input"
              required
            />
            <button type="submit" disabled={isLoading} className="send-button">
              {isLoading ? '送信中...' : '送信'}
            </button>
          </div>
        </form>
      </main>
    </div>
  )
}

export default App
