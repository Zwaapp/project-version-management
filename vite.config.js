import { defineConfig, loadEnv } from 'vite'
import laravel from 'laravel-vite-plugin';
export default defineConfig(({ command, mode }) => {
    const env = loadEnv(mode, process.cwd(), '')

    let vite_config = {
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
        ]
    };

    if (env.APP_ENV === 'local') {
        vite_config.server = {
            // Respond to all network requests (equivalent to '0.0.0.0')
            host: true,
            // We need a strict port to match on PHP side
            strictPort: true,
            port: env.VITE_PORT,
            hmr: {
                // Force the Vite client to connect via SSL
                // This will also force a "https://" URL in the public/hot file
                protocol: 'wss',
                // The host where the Vite dev server can be accessed
                // This will also force this host to be written to the public/hot file
                host: `${process.env.DDEV_HOSTNAME}`
            }
        }
    }

    return vite_config;
});
