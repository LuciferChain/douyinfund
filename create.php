<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>创作者基金 - 创建代币</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.1/ethers.umd.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: #f0f0f0; }
        input, button { width: 100%; padding: 10px; margin: 10px 0; }
        button { background: #007bff; color: white; border: none; cursor: pointer; }
        #status { margin-top: 20px; color: green; }
    </style>
</head>
<body>
    <h1>创作者基金 - 创建代币</h1>
    <p>连接 BSC 钱包，填写信息，一键创建。<br><strong>3% 交易税将自动流向基金地址：0x0867a6E7A280ac6A7349960a9e2661969367F010</strong></p>
    
    <input id="name" placeholder="代币名称（如：MyMeme）" required>
    <input id="symbol" placeholder="代币符号（如：MYM）" required>
    <input id="cid" placeholder="IPFS CID（图片元数据，先去 pinata.cloud 上传图片+描述获取 CID）" required>
    <button onclick="connectAndCreate()">连接钱包并创建代币</button>
    
    <div id="status"></div>
    <a href="index.php">← 返回主页面查看代币列表</a>

    <script>
        const PORTAL_ADDRESS = "0xe2cE6ab80874Fa9Fa2aAE65D277Dd6B8e65C9De0";
        const BENEFICIARY = "0x0867a6E7A280ac6A7349960a9e2661969367F010"; // 你的基金地址
        const TAX_RATE = 300; // 3% = 300 basis points
        
        // 请从以下地方复制最新 ABI（Portal 合约的完整 ABI JSON 数组）：
        // 1. https://docs.flap.sh/flap/developers/deployed-contract-addresses （有 abi.json 下载链接）
        // 2. 或 BscScan: https://bscscan.com/address/0xe2cE6ab80874Fa9Fa2aAE65D277Dd6B8e65C9De0#code
        // 找到创建函数（可能叫 newTokenV2 / newTokenV5 等），复制整个 ABI 数组粘贴下面
        const ABI = [{"inputs":[{"internalType":"address","name":"_logic","type":"address"},{"internalType":"address","name":"admin_","type":"address"},{"internalType":"bytes","name":"_data","type":"bytes"}],"stateMutability":"payable","type":"constructor"},{"anonymous":false,"inputs":[{"indexed":false,"internalType":"address","name":"previousAdmin","type":"address"},{"indexed":false,"internalType":"address","name":"newAdmin","type":"address"}],"name":"AdminChanged","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"beacon","type":"address"}],"name":"BeaconUpgraded","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"implementation","type":"address"}],"name":"Upgraded","type":"event"},{"stateMutability":"payable","type":"fallback"},{"stateMutability":"payable","type":"receive"}]
;

        async function connectAndCreate() {
            const status = document.getElementById('status');
            status.innerText = '连接中...';

            if (!window.ethereum) {
                return status.innerText = '请安装 MetaMask 或其他 BSC 兼容钱包！';
            }

            try {
                const provider = new ethers.BrowserProvider(window.ethereum);
                await provider.send("eth_requestAccounts", []);
                const signer = await provider.getSigner();
                const userAddress = await signer.getAddress();

                const network = await provider.getNetwork();
                if (network.chainId !== 56n) {
                    return status.innerText = '请切换到 BNB Chain (BSC 主网)！';
                }

                status.innerText = '准备创建...';

                const portal = new ethers.Contract(PORTAL_ADDRESS, ABI, signer);

                // 示例参数（根据最新 ABI 调整！常见参数包括 name, symbol, meta CID, taxRate, beneficiary, mktBps=10000 全部分给 beneficiary）
                // 你需要根据实际函数签名调整 args 和 value（初始流动性 BNB，通常 0.1-1 BNB）
                const tx = await portal.someCreationFunction( // 替换为实际函数名，如 newTokenV2
                    "param1", // 根据 ABI 调整
                    {
                        value: ethers.parseEther("0.01"), // 示例：提供 0.5 BNB 初始流动性，可让用户输入
                        gasLimit: 5000000
                    }
                );

                status.innerText = '交易发送中... Hash: ' + tx.hash;
                await tx.wait();
                status.innerText = '创建成功！查看主页面列表，或去 https://flap.sh/board 看详情。';
            } catch (err) {
                console.error(err);
                status.innerText = '失败: ' + (err.message || err);
            }
        }
    </script>
</body>
</html>
