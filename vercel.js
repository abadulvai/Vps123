 // Code by @AzR_Projects

export default {
    async fetch(request) {
        const url = new URL(request.url);
        const text = (url.searchParams.get('text') || '').trim();

        if (!text) {
            return new Response(JSON.stringify({ error: '⚠️ Missing text input. Provide a valid query.' }), { status: 400, headers: { 'Content-Type': 'application/json' } });
        }

        const apiUrl = 'https://api.deepinfra.com/v1/openai/chat/completions';
        const payload = {
            model: 'deepseek-ai/DeepSeek-R1',
            messages: [
                { role: 'system', content: 'Be a helpful assistant' },
                { role: 'user', content: text }
            ],
            stream: false
        };

        try {
            const response = await fetch(apiUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'User-Agent': 'Mozilla/5.0' },
                body: JSON.stringify(payload)
            });

            if (!response.ok) {
                return new Response(JSON.stringify({ error: `❌ HTTP Error: ${response.status} - API issue detected.` }), { status: response.status, headers: { 'Content-Type': 'application/json' } });
            }

            const data = await response.json();

            if (!data.choices || !data.choices[0]) {
                return new Response(JSON.stringify({ error: '⚠️ Unexpected response format from API.' }), { status: 500, headers: { 'Content-Type': 'application/json' } });
            }

            return new Response(JSON.stringify({
                credit: '@AzR_projects',
                response: data.choices[0].message.content
            }), { headers: { 'Content-Type': 'application/json' } });

        } catch (error) {
            return new Response(JSON.stringify({ error: '🚨 API is unreachable. Try again later.' }), { status: 500, headers: { 'Content-Type': 'application/json' } });
        }
    }
};
